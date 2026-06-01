<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\DeferredRevenue;
use App\Models\DeferredRevenueSchedule;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeferredRevenueService
{
    /**
     * Generate an amortization schedule for a deferred revenue contract.
     */
    public function generateSchedule(DeferredRevenue $deferredRevenue): void
    {
        // Remove any existing pending schedules
        $deferredRevenue->schedules()
            ->where('status', 'pending')
            ->delete();

        $totalAmount = (float) $deferredRevenue->total_amount;
        $totalPeriods = $deferredRevenue->total_periods;
        $startDate = Carbon::parse($deferredRevenue->period_start);
        $endDate = Carbon::parse($deferredRevenue->period_end);

        if ($totalPeriods <= 0 || $totalAmount <= 0) {
            return;
        }

        $monthlyAmount = round($totalAmount / $totalPeriods, 2);
        $lastPeriodAmount = $totalAmount - ($monthlyAmount * ($totalPeriods - 1));

        for ($i = 1; $i <= $totalPeriods; $i++) {
            $periodStart = $startDate->copy()->addMonths($i - 1);
            $periodEnd = $startDate->copy()->addMonths($i)->subDay();

            // Ensure last period ends at contract end date
            if ($i === $totalPeriods) {
                $periodEnd = $endDate->copy();
            }

            $plannedAmount = ($i === $totalPeriods) ? $lastPeriodAmount : $monthlyAmount;

            DeferredRevenueSchedule::create([
                'period_number' => $i,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'planned_amount' => $plannedAmount,
                'recognized_amount' => 0,
                'status' => 'pending',
                'deferred_revenue_id' => $deferredRevenue->id,
            ]);
        }

        // Refresh parent totals after schedule regeneration
        $deferredRevenue->refreshTotals();
    }

    /**
     * Recognize revenue for a specific schedule line.
     * Creates the journal entry: Dr Deferred Revenue (Liability), Cr Revenue.
     */
    public function recognizeRevenue(DeferredRevenueSchedule $schedule, ?string $recognizedDate = null, ?int $userId = null): ?JournalEntry
    {
        if ($schedule->status === 'recognized') {
            return $schedule->journalEntry;
        }

        $deferredRevenue = $schedule->deferredRevenue;
        if (!$deferredRevenue) {
            return null;
        }

        $companyId = $deferredRevenue->company_id;
        $amount = (float) $schedule->planned_amount;

        if ($amount <= 0) {
            return null;
        }

        // Resolve accounts: prefer contract-level overrides, fall back to AccountMapping
        $liabilityAccount = $deferredRevenue->deferred_revenue_account_id
            ? Account::find($deferredRevenue->deferred_revenue_account_id)
            : AccountMapping::getAccountMapping('deferred_revenue', 'deferred_revenue_liability', $companyId);

        $revenueAccount = $deferredRevenue->revenue_account_id
            ? Account::find($deferredRevenue->revenue_account_id)
            : AccountMapping::getAccountMapping('deferred_revenue', 'deferred_revenue_recognition', $companyId);

        if (!$liabilityAccount || !$revenueAccount) {
            return null;
        }

        // Guard against CLI context where Auth::id() returns null
        $actingUserId = $userId ?? Auth::id();
        if (!$actingUserId) {
            return null;
        }

        DB::beginTransaction();

        try {
            $journalEntry = JournalEntry::create([
                'entry_number' => $this->generateEntryNumber(),
                'date' => $recognizedDate ?? now(),
                'description' => "Deferred Revenue Amortization: {$deferredRevenue->contract_number} - Period {$schedule->period_number}",
                'amount' => $amount,
                'total_amount' => $amount,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'deferred_revenue',
                'reference_type' => DeferredRevenue::class,
                'reference_id' => $deferredRevenue->id,
                'posted_by_user_id' => $actingUserId,
                'posted_at' => now(),
                'company_id' => $companyId,
                'created_by_user_id' => $actingUserId,
                'updated_by_user_id' => $actingUserId,
            ]);

            // Dr: Deferred Revenue (Liability) — reduce the liability
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $liabilityAccount->id,
                'debit' => $amount,
                'credit' => 0,
                'notes' => "Amortization period {$schedule->period_number}",
            ]);

            // Cr: Revenue — recognize the revenue
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $amount,
                'notes' => "Amortization period {$schedule->period_number}",
            ]);

            // Update schedule line
            $schedule->update([
                'recognized_amount' => $amount,
                'recognized_date' => $recognizedDate ?? now(),
                'status' => 'recognized',
                'journal_entry_id' => $journalEntry->id,
            ]);

            // Refresh parent totals
            $deferredRevenue->refreshTotals();

            DB::commit();

            return $journalEntry;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse a recognized revenue entry.
     * Deletes the journal entry and resets the schedule line.
     */
    public function reverseRecognition(DeferredRevenueSchedule $schedule): bool
    {
        if ($schedule->status !== 'recognized') {
            return false;
        }

        DB::beginTransaction();

        try {
            // Delete journal entry
            if ($schedule->journal_entry_id) {
                $journalEntry = JournalEntry::find($schedule->journal_entry_id);
                if ($journalEntry) {
                    $journalEntry->items()->delete();
                    $journalEntry->delete();
                }
            }

            // Reset schedule line
            $schedule->update([
                'recognized_amount' => 0,
                'recognized_date' => null,
                'status' => 'pending',
                'journal_entry_id' => null,
            ]);

            // Refresh parent totals
            $schedule->deferredRevenue->refreshTotals();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Bulk recognize all due schedule lines.
     * Returns the number of schedules recognized.
     */
    public function recognizeDue(?int $companyId = null): int
    {
        $query = DeferredRevenueSchedule::where('status', 'pending')
            ->whereHas('deferredRevenue', function ($q) use ($companyId) {
                $q->where('status', 'active');
                if ($companyId) {
                    $q->where('company_id', $companyId);
                }
            })
            ->where('period_end', '<=', now());

        $schedules = $query->get();
        $count = 0;

        foreach ($schedules as $schedule) {
            try {
                $this->recognizeRevenue($schedule);
                $count++;
            } catch (\Exception $e) {
                \Log::warning("Failed to recognize deferred revenue schedule #{$schedule->id}: " . $e->getMessage());
            }
        }

        return $count;
    }

    /**
     * Generate a unique journal entry number.
     */
    protected function generateEntryNumber(): string
    {
        $prefix = 'DR';
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
