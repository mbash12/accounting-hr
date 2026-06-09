<?php

namespace App\Services;

use App\Models\Account;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use App\Models\BankReconciliationItem;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BankReconciliationService
{
    /**
     * Import bank statement from Excel, auto-match against existing journal entries.
     *
     * Template columns: Date | Description | Reference | Account Code | Debit | Credit
     */
    public function importFromExcel(string $filePath, int $bankAccountId, ?int $companyId): array
    {
        $bankAccount = BankAccount::findOrFail($bankAccountId);
        $bankLines = $this->readBankStatement($filePath);

        if (empty($bankLines)) {
            throw new \RuntimeException(__('No data rows found in the uploaded file.'));
        }

        return DB::transaction(function () use ($bankLines, $bankAccount, $companyId) {
            $totalDebit = collect($bankLines)->sum('debit');
            $totalCredit = collect($bankLines)->sum('credit');
            $statementBalance = round($totalCredit - $totalDebit, 2);
            $userId = Auth::id();

            $reconciliation = BankReconciliation::create([
                'statement_date' => now()->format('Y-m-d'),
                'statement_balance' => (string) $statementBalance,
                'book_balance' => '0',
                'reconciliation_date' => now()->format('Y-m-d'),
                'status' => 'in_progress',
                'reconciled_at' => null,
                'difference' => '0',
                'bank_account_id' => $bankAccount->id,
                'reconciled_by_user_id' => null,
                'company_id' => $companyId ?? $bankAccount->company_id,
                'created_by_user_id' => $userId,
            ]);

            $matched = 0;
            $unmatched = 0;
            $usedJournalEntryIds = [];

            foreach ($bankLines as $line) {
                $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];

                $matchStatus = 'unmatched';

                $existing = $this->findExistingJournal($line, $companyId, $usedJournalEntryIds);
                if ($existing) {
                    $matchStatus = 'matched';
                    $usedJournalEntryIds[] = $existing['journal_entry_id'];
                }

                BankReconciliationItem::create([
                    'bank_reconciliation_id' => $reconciliation->id,
                    'type' => $line['type'],
                    'bank_date' => $line['date'],
                    'bank_description' => $line['description'],
                    'bank_debit' => $line['debit'],
                    'bank_credit' => $line['credit'],
                    'reference_no' => $line['reference_no'] ?? null,
                    'account_code' => $line['account_code'] ?? null,
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'match_status' => $matchStatus,
                ]);

                if ($matchStatus === 'matched') {
                    $matched++;
                } else {
                    $unmatched++;
                }
            }

            $unmatchedAmount = $reconciliation->items()
                ->where('match_status', '!=', 'matched')
                ->get()
                ->sum(fn ($item) => $item->bank_debit > 0 ? (float) $item->bank_debit : (float) $item->bank_credit);

            $reconciliation->update([
                'difference' => (string) round($unmatchedAmount, 2),
                'status' => 'in_progress',
                'reconciled_at' => null,
                'reconciled_by_user_id' => null,
            ]);

            return ['reconciliation' => $reconciliation];
        });
    }

    private function findExistingJournal(array $line, ?int $companyId, array $usedJournalEntryIds = []): ?array
    {
        $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
        $accountCode = $line['account_code'] ?? null;
        $referenceNo = $line['reference_no'] ?? null;
        $isIncoming = $line['type'] === 'incoming';
        $date = $line['date'] ?? null;

        if (!$accountCode || $amount <= 0 || !$date) {
            return null;
        }

        $account = Account::where('code', $accountCode)
            ->where('company_id', $companyId)
            ->where('is_header', false)
            ->first();

        if (!$account) {
            return null;
        }

        $query = JournalEntryItem::where('account_id', $account->id)
            ->whereHas('journalEntry', function ($q) use ($companyId, $date, $usedJournalEntryIds) {
                $q->where('company_id', $companyId)
                  ->whereDate('date', $date)
                  ->when($usedJournalEntryIds, fn ($q) => $q->whereNotIn('id', $usedJournalEntryIds));
            });

        if ($isIncoming) {
            $query->where('credit', $amount);
        } else {
            $query->where('debit', $amount);
        }

        if ($referenceNo) {
            $query->whereHas('journalEntry', function ($q) use ($referenceNo) {
                $q->where('reference_no', $referenceNo)
                  ->orWhere('entry_number', $referenceNo);
            });
        }

        $existingItem = $query->first();

        if ($existingItem) {
            return [
                'journal_entry_item_id' => $existingItem->id,
                'journal_entry_id' => $existingItem->journal_entry_id,
            ];
        }

        return null;
    }

    /**
     * Create a journal entry for unmatched bank transactions.
     */
    private function createJournalEntry(array $line, BankAccount $bankAccount, string $accountCode, ?int $companyId, int $userId, int $reconciliationId): void
    {
        $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
        $isIncoming = $line['type'] === 'incoming';

        $contraAccount = Account::where('code', $accountCode)
            ->where('company_id', $companyId)
            ->where('is_header', false)
            ->first();

        if (!$contraAccount) {
            throw new \RuntimeException(__('Account with code ":code" not found.', ['code' => $accountCode]));
        }

        $bankCoa = $bankAccount->coaAccount;

        if (!$bankCoa) {
            throw new \RuntimeException(__('Bank account ":name" is not linked to a COA account. Please set the COA Account on the bank account master.', ['name' => $bankAccount->account_name]));
        }

        $entryNumber = 'BANK-RECON-' . $reconciliationId . '-' . substr(uniqid(), -6);

        $journalEntry = JournalEntry::create([
            'entry_number' => $entryNumber,
            'date' => $line['date'],
            'reference_no' => $line['reference_no'] ?: null,
            'description' => $line['description'] ?: 'Bank reconciliation - ' . ($isIncoming ? 'incoming' : 'outgoing'),
            'amount' => $amount,
            'total_amount' => $amount,
            'status' => 'draft',
            'is_posted' => false,
            'sub_module' => 'bank_reconciliation',
            'company_id' => $companyId,
            'created_by_user_id' => $userId,
        ]);

        if ($isIncoming) {
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $bankCoa->id,
                'debit' => $amount,
                'credit' => 0,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $contraAccount->id,
                'debit' => 0,
                'credit' => $amount,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
        } else {
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $contraAccount->id,
                'debit' => $amount,
                'credit' => 0,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $bankCoa->id,
                'debit' => 0,
                'credit' => $amount,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
        }
    }

    /**
     * Read bank statement Excel.
     *
     * Template: Date | Description | Reference | Account Code | Debit | Credit
     */
    private function readBankStatement(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (count($rows) < 2) {
            throw new \RuntimeException(__('The uploaded file is empty.'));
        }

        $headers = array_map(fn($h) => is_string($h) ? trim($h) : '', $rows[0]);
        $normalized = array_map(fn($h) => strtolower(str_replace(' ', '', $h)), $headers);

        $getCol = function (array $aliases) use ($normalized): ?int {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $normalized, true);
                if ($idx !== false) {
                    return $idx;
                }
            }
            return null;
        };

        $colDate = $getCol(['tanggal', 'date', 'tgl', 'transactiondate']);
        $colDesc = $getCol(['deskripsi', 'description', 'keterangan', 'uraian', 'narration']);
        $colDebit = $getCol(['debit', 'debet', 'outgoing', 'pengeluaran', 'debit(outgoing)', 'debitoutgoing']);
        $colCredit = $getCol(['kredit', 'credit', 'incoming', 'pemasukan', 'credit(incoming)', 'creditincoming']);
        $colRef = $getCol(['referensi', 'ref', 'reference', 'noreferensi', 'referenceno', 'nobukti']);
        $colAccountCode = $getCol(['kode_akun', 'kodeakun', 'accountcode', 'nocoa', 'coa', 'account_code']);
        $colAccountName = $getCol(['nama_akun', 'namaakun', 'accountname', 'nama_coa']);
        $colNotes = $getCol(['catatan', 'notes', 'keterangan']);
        if ($colDate === null || $colDesc === null || ($colDebit === null && $colCredit === null)) {
            throw new \RuntimeException(__('Required columns not found. Need at least: Date, Description, and one of Debit/Credit.'));
        }

        $lines = [];
        $errors = [];

        for ($i = 1, $len = count($rows); $i < $len; $i++) {
            $rowNumber = $i + 1;
            $row = $rows[$i];

            $date = trim((string) ($row[$colDate] ?? ''));
            $description = trim((string) ($row[$colDesc] ?? ''));
            $referenceNo = $colRef !== null ? trim((string) ($row[$colRef] ?? '')) : '';
            $accountCode = $colAccountCode !== null ? trim((string) ($row[$colAccountCode] ?? '')) : '';
            $notes = $colNotes !== null ? trim((string) ($row[$colNotes] ?? '')) : '';
            $debitRaw = $colDebit !== null ? $this->parseAmount($row[$colDebit] ?? 0) : 0;
            $creditRaw = $colCredit !== null ? $this->parseAmount($row[$colCredit] ?? 0) : 0;

            if ($debitRaw == 0 && $creditRaw == 0) {
                continue;
            }

            if ($date === '') {
                $errors[] = __('Row :row: Date is empty.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw < 0 || $creditRaw < 0) {
                $errors[] = __('Row :row: Negative amounts are not allowed.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw > 0 && $creditRaw > 0) {
                $errors[] = __('Row :row: Cannot have both debit and credit.', ['row' => $rowNumber]);
                continue;
            }

            $lines[] = [
                'type' => $debitRaw > 0 ? 'incoming' : 'outgoing',
                'date' => $date,
                'description' => $description ?: null,
                'reference_no' => $referenceNo ?: null,
                'account_code' => $accountCode ?: null,
                'notes' => $notes ?: null,
                'debit' => round($debitRaw, 2),
                'credit' => round($creditRaw, 2),
            ];
        }

        if (! empty($errors)) {
            throw new \RuntimeException(implode("\n", $errors));
        }

        return $lines;
    }

    /**
     * Recalculate the difference based on current match status of items.
     */
    public function recalculateDifference(BankReconciliation $reconciliation): void
    {
        $amounts = $this->getCalculatedAmounts($reconciliation);
        $reconciliation->update([
            'difference' => (string) round($amounts['unmatched_amount'], 2),
        ]);
    }

    /**
     * Get calculated amounts for live display.
     */
    public function getCalculatedAmounts(BankReconciliation $reconciliation): array
    {
        $items = $reconciliation->items;

        $statementTotal = $items->sum(function ($item) {
            return $item->bank_debit > 0 ? (float) $item->bank_debit : (float) $item->bank_credit;
        });

        $matchedAmount = 0;
        $unmatchedAmount = 0;

        foreach ($items as $item) {
            $bankAmount = $item->bank_debit > 0 ? (float) $item->bank_debit : (float) $item->bank_credit;

            if ($item->match_status === 'matched') {
                $matchedAmount += $bankAmount;
            } else {
                $unmatchedAmount += $bankAmount;
            }
        }

        return [
            'statement_total' => round($statementTotal, 2),
            'matched_amount' => round($matchedAmount, 2),
            'unmatched_amount' => round($unmatchedAmount, 2),
        ];
    }

    /**
     * Process all matches: create journal entries for unmatched items with account_code.
     */
    public function processMatches(BankReconciliation $reconciliation): array
    {
        $items = $reconciliation->items()
            ->where('match_status', 'unmatched')
            ->whereNull('imported_at')
            ->get();
        $processed = 0;
        $errors = [];

        foreach ($items as $item) {
            try {
                $bankAccount = $reconciliation->bankAccount;
                $line = [
                    'type' => $item->type,
                    'date' => $item->bank_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    'description' => $item->bank_description,
                    'reference_no' => $item->reference_no,
                    'debit' => (float) $item->bank_debit,
                    'credit' => (float) $item->bank_credit,
                ];

                if ($item->account_code) {
                    DB::transaction(function () use ($item, $line, $bankAccount, $reconciliation) {
                        $this->createJournalEntry(
                            $line,
                            $bankAccount,
                            $item->account_code,
                            $reconciliation->company_id,
                            Auth::id(),
                            $reconciliation->id
                        );
                        $item->update(['imported_at' => now()]);
                    });
                }
                $processed++;
            } catch (\Exception $e) {
                $errors[] = ($item->bank_description ?? 'Item #'.$item->id) . ': ' . $e->getMessage();
            }
        }

        $this->recalculateDifference($reconciliation);

        $remaining = $reconciliation->items()
            ->where('match_status', 'unmatched')
            ->whereNull('imported_at')
            ->whereNotNull('account_code')
            ->count();
        if ($remaining === 0) {
            $reconciliation->update([
                'status' => 'completed',
                'reconciled_at' => now(),
                'reconciled_by_user_id' => Auth::id(),
            ]);
        }

        return ['processed' => $processed, 'errors' => $errors];
    }

    protected function parseAmount(mixed $value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }
        $str = trim((string) $value);
        if ($str === '') {
            return 0.0;
        }
        $dotCount = substr_count($str, '.');
        $commaCount = substr_count($str, ',');

        if ($dotCount > 1) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif ($commaCount > 1) {
            $str = str_replace(',', '', $str);
        } elseif ($dotCount === 1 && $commaCount === 1) {
            $lastComma = strrpos($str, ',');
            $lastDot = strrpos($str, '.');
            if ($lastComma > $lastDot) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif ($dotCount === 1 && $commaCount === 0) {
            $parts = explode('.', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace('.', '', $str);
            }
        } elseif ($commaCount === 1 && $dotCount === 0) {
            $parts = explode(',', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        }

        return (float) str_replace(' ', '', $str);
    }
}
