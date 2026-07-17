<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\Company;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\PeriodClosing;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeriodClosingService
{
    /**
     * Resolve calendar-year bounds for a fiscal year label (v1 = calendar year).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public function yearBounds(int $year): array
    {
        return [
            Carbon::create($year, 1, 1)->startOfDay(),
            Carbon::create($year, 12, 31)->endOfDay(),
        ];
    }

    public function isClosed(int $companyId, CarbonInterface|string $date): bool
    {
        $day = Carbon::parse($date)->toDateString();

        return PeriodClosing::query()
            ->where('company_id', $companyId)
            ->where('period_type', PeriodClosing::TYPE_YEARLY)
            ->where('status', PeriodClosing::STATUS_CLOSED)
            ->whereDate('start_date', '<=', $day)
            ->whereDate('end_date', '>=', $day)
            ->exists();
    }

    /**
     * True when a prior fiscal year was closed with a real closing journal,
     * so reports should not inject dynamic Prior Retained Earnings.
     */
    public function hasPostedClosingBefore(int $companyId, CarbonInterface|string $beforeDate): bool
    {
        $day = Carbon::parse($beforeDate)->toDateString();

        return PeriodClosing::query()
            ->where('company_id', $companyId)
            ->where('period_type', PeriodClosing::TYPE_YEARLY)
            ->where('status', PeriodClosing::STATUS_CLOSED)
            ->whereNotNull('closing_journal_entry_id')
            ->whereDate('end_date', '<', $day)
            ->exists();
    }

    public function assertOpen(int $companyId, CarbonInterface|string $date): void
    {
        if ($this->isClosed($companyId, $date)) {
            $year = Carbon::parse($date)->year;
            throw ValidationException::withMessages([
                'date' => __("Fiscal year :year is closed (Tutup Buku). Reopen the year to change transactions dated in that period.", [
                    'year' => $year,
                ]),
            ]);
        }
    }

    public function findYearClosing(int $companyId, int $year): ?PeriodClosing
    {
        [$start, $end] = $this->yearBounds($year);

        return PeriodClosing::query()
            ->where('company_id', $companyId)
            ->where('period_type', PeriodClosing::TYPE_YEARLY)
            ->whereDate('start_date', $start->toDateString())
            ->whereDate('end_date', $end->toDateString())
            ->first();
    }

    public function resolveRetainedEarningsAccount(int $companyId): Account
    {
        $account = AccountMapping::getAccountMapping('period_closing', 'retained_earnings', $companyId);

        if (!$account) {
            throw ValidationException::withMessages([
                'retained_earnings' => __('Map Retained Earnings (Laba Ditahan) under Account Mapping → Period Closing / Tutup Buku before closing the year.'),
            ]);
        }

        return $account;
    }

    public function closeYear(int $companyId, int $year, ?string $description = null): PeriodClosing
    {
        if (!Company::query()->whereKey($companyId)->exists()) {
            throw ValidationException::withMessages([
                'company_id' => __('Company not found.'),
            ]);
        }

        [$start, $end] = $this->yearBounds($year);
        $existing = $this->findYearClosing($companyId, $year);

        if ($existing?->isClosed()) {
            throw ValidationException::withMessages([
                'year' => __('Fiscal year :year is already closed.', ['year' => $year]),
            ]);
        }

        $reAccount = $this->resolveRetainedEarningsAccount($companyId);

        return DB::transaction(function () use ($companyId, $year, $description, $start, $end, $existing, $reAccount) {
            $lines = $this->buildClosingLines($companyId, $start, $end, $reAccount);

            $journal = $this->createClosingJournal(
                $companyId,
                $year,
                $end->toDateString(),
                $description,
                $lines,
            );

            $payload = [
                'period_type' => PeriodClosing::TYPE_YEARLY,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'status' => PeriodClosing::STATUS_CLOSED,
                'closed_at' => now(),
                'closed_by_user_id' => Auth::id(),
                'description' => $description ?? __('Tutup Buku :year', ['year' => $year]),
                'closing_journal_entry_id' => $journal->id,
                'company_id' => $companyId,
                'reopened_at' => null,
                'reopened_by_user_id' => null,
                'reopen_reason' => null,
            ];

            if ($existing) {
                $existing->update($payload);
                $period = $existing->fresh(['closingJournalEntry', 'closedByUser']);
            } else {
                $period = PeriodClosing::create($payload)->load(['closingJournalEntry', 'closedByUser']);
            }

            $journal->update([
                'reference_type' => PeriodClosing::class,
                'reference_id' => $period->id,
            ]);

            return $period;
        });
    }

    public function reopenYear(PeriodClosing $period, string $reason): PeriodClosing
    {
        if ($period->period_type !== PeriodClosing::TYPE_YEARLY) {
            throw ValidationException::withMessages([
                'period' => __('Only yearly period closings can be reopened in this version.'),
            ]);
        }

        if (!$period->isClosed()) {
            throw ValidationException::withMessages([
                'period' => __('This fiscal year is not closed.'),
            ]);
        }

        if (trim($reason) === '') {
            throw ValidationException::withMessages([
                'reopen_reason' => __('A reason is required to reopen the year.'),
            ]);
        }

        return DB::transaction(function () use ($period, $reason) {
            $journalId = $period->closing_journal_entry_id;

            // Unlock first so removing the closing JE is not blocked by the period guard.
            $period->update([
                'status' => PeriodClosing::STATUS_OPEN,
                'reopened_at' => now(),
                'reopened_by_user_id' => Auth::id(),
                'reopen_reason' => $reason,
                'closing_journal_entry_id' => null,
                'closed_at' => null,
                'closed_by_user_id' => null,
            ]);

            if ($journalId) {
                $journal = JournalEntry::with('items')->find($journalId);
                if ($journal) {
                    $journal->items()->delete();
                    $journal->delete();
                }
            }

            return $period->fresh(['reopenedByUser']);
        });
    }

    /**
     * @return list<array{account_id: int, debit: float, credit: float, notes: ?string}>
     */
    protected function buildClosingLines(int $companyId, Carbon $start, Carbon $end, Account $reAccount): array
    {
        $movements = JournalEntryItem::query()
            ->select(
                'account_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->whereHas('journalEntry', function ($q) use ($companyId, $start, $end) {
                $q->where('company_id', $companyId)
                    ->where('is_posted', true)
                    ->whereDate('date', '>=', $start->toDateString())
                    ->whereDate('date', '<=', $end->toDateString());
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        $accounts = Account::query()
            ->where('company_id', $companyId)
            ->whereRaw("LEFT(code, 1) IN ('4', '5', '6', '7', '8', '9')")
            ->orderBy('code')
            ->get();

        $lines = [];
        $netToRetained = 0.0;

        foreach ($accounts as $account) {
            $mov = $movements->get($account->id);
            $debit = (float) ($mov->total_debit ?? 0);
            $credit = (float) ($mov->total_credit ?? 0);
            $root = substr((string) $account->code, 0, 1);
            $isDebitNormal = in_array($root, ['5', '6', '7', '9'], true);

            // Credit-normal (revenue/other income): close by debiting remaining credit balance
            // Debit-normal (expense/COGS): close by crediting remaining debit balance
            if ($isDebitNormal) {
                $balance = round($debit - $credit, 2);
                if (abs($balance) < 0.01) {
                    continue;
                }
                if ($balance > 0) {
                    $lines[] = [
                        'account_id' => $account->id,
                        'debit' => 0.0,
                        'credit' => $balance,
                        'notes' => __('Close :name', ['name' => $account->name]),
                    ];
                    $netToRetained -= $balance;
                } else {
                    $lines[] = [
                        'account_id' => $account->id,
                        'debit' => abs($balance),
                        'credit' => 0.0,
                        'notes' => __('Close :name', ['name' => $account->name]),
                    ];
                    $netToRetained += abs($balance);
                }
            } else {
                $balance = round($credit - $debit, 2);
                if (abs($balance) < 0.01) {
                    continue;
                }
                if ($balance > 0) {
                    $lines[] = [
                        'account_id' => $account->id,
                        'debit' => $balance,
                        'credit' => 0.0,
                        'notes' => __('Close :name', ['name' => $account->name]),
                    ];
                    $netToRetained += $balance;
                } else {
                    $lines[] = [
                        'account_id' => $account->id,
                        'debit' => 0.0,
                        'credit' => abs($balance),
                        'notes' => __('Close :name', ['name' => $account->name]),
                    ];
                    $netToRetained -= abs($balance);
                }
            }
        }

        $netToRetained = round($netToRetained, 2);

        if (abs($netToRetained) >= 0.01) {
            $lines[] = [
                'account_id' => $reAccount->id,
                'debit' => $netToRetained < 0 ? abs($netToRetained) : 0.0,
                'credit' => $netToRetained > 0 ? $netToRetained : 0.0,
                'notes' => __('Net income to Retained Earnings'),
            ];
        }

        if ($lines === []) {
            // Still allow close with a zero JE tip to RE for empty years — use a balanced placeholder? Better reject or allow empty close with no JE.
            // Allow empty year close with no journal lines except a note JE is skipped.
        }

        return $lines;
    }

    /**
     * @param list<array{account_id: int, debit: float, credit: float, notes: ?string}> $lines
     */
    protected function createClosingJournal(
        int $companyId,
        int $year,
        string $date,
        ?string $description,
        array $lines,
    ): JournalEntry {
        $totalDebit = round(collect($lines)->sum('debit'), 2);
        $totalCredit = round(collect($lines)->sum('credit'), 2);

        if (abs($totalDebit - $totalCredit) >= 0.01) {
            throw ValidationException::withMessages([
                'journal' => __('Closing journal is out of balance (debit :d, credit :c).', [
                    'd' => $totalDebit,
                    'c' => $totalCredit,
                ]),
            ]);
        }

        $journal = JournalEntry::create([
            'entry_number' => $this->generateEntryNumber(),
            'date' => $date,
            'reference_no' => 'TB-' . $year,
            'description' => $description ?? __('Tutup Buku :year', ['year' => $year]),
            'amount' => $totalDebit,
            'total_amount' => $totalDebit,
            'status' => 'posted',
            'is_posted' => true,
            'sub_module' => 'period_closing',
            'reference_type' => PeriodClosing::class,
            'reference_id' => null,
            'posted_by_user_id' => Auth::id(),
            'posted_at' => now(),
            'company_id' => $companyId,
            'created_by_user_id' => Auth::id() ?? 1,
            'updated_by_user_id' => Auth::id() ?? 1,
        ]);

        foreach ($lines as $line) {
            if (($line['debit'] ?? 0) <= 0 && ($line['credit'] ?? 0) <= 0) {
                continue;
            }
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $line['account_id'],
                'debit' => $line['debit'],
                'credit' => $line['credit'],
                'notes' => $line['notes'] ?? null,
            ]);
        }

        // Empty year: still create a posted header with zero amount for audit trail
        return $journal;
    }

    protected function generateEntryNumber(): string
    {
        $prefix = 'JE';
        $date = now()->format('Ymd');

        $lastEntry = JournalEntry::where('entry_number', 'like', $prefix . $date . '%')
            ->orderBy('entry_number', 'desc')
            ->first();

        if ($lastEntry) {
            $lastNumber = (int) substr($lastEntry->entry_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }
}
