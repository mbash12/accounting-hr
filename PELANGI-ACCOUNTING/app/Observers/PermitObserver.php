<?php

namespace App\Observers;

use App\Models\EmployeeLeaveQuota;
use App\Models\Permit;
use Illuminate\Database\QueryException;

class PermitObserver
{
    /**
     * Leave types that consume annual leave quota.
     * 'annual_leave' is the legacy value; 'annual' is the current value used by NUXI.
     */
    private const ANNUAL_LEAVE_TYPES = ['annual', 'annual_leave'];

    private function isAnnualLeave(Permit $permit): bool
    {
        return in_array($permit->type, self::ANNUAL_LEAVE_TYPES, true);
    }

    /**
     * When a Permit is approved, auto-update the employee's leave quota.
     * Only annual leave types affect quota (other types are permits/izin).
     */
    public function created(Permit $permit): void
    {
        // If a permit is created directly with status='approved' (e.g. via API or seeder)
        if ($permit->status === 'approved' && $this->isAnnualLeave($permit)) {
            $this->deductQuota($permit);
        }
    }

    /**
     * When a Permit's status changes, auto-update the employee's leave quota.
     * Only annual leave types affect quota (other types are permits/izin).
     */
    public function updated(Permit $permit): void
    {
        if (! $permit->isDirty('status')) {
            return;
        }

        // Only annual leave type consumes quota
        if (! $this->isAnnualLeave($permit)) {
            return;
        }

        if ($permit->status === 'approved') {
            $this->deductQuota($permit);
        } elseif ($permit->getOriginal('status') === 'approved') {
            // Status changed away from approved (e.g., to rejected) — refund the quota
            $this->refundQuota($permit);
        }
    }

    /**
     * Deduct the permit's duration from the employee's used_quota.
     */
    private function deductQuota(Permit $permit): void
    {
        $duration = $this->calculateDuration($permit);
        if ($duration <= 0) {
            return;
        }

        $year = $permit->start_date?->year ?? now()->year;
        $quota = $this->findOrCreateQuota($permit->employee_id, $year, $permit->company_id);

        // Use withoutEvents to avoid infinite loop
        $quota->withoutEvents(function () use ($quota, $duration) {
            $quota->increment('used_quota', $duration);
            $quota->decrement('remaining_quota', $duration);
        });
    }

    /**
     * Refund the permit's duration (when approval is reversed).
     */
    private function refundQuota(Permit $permit): void
    {
        $duration = $this->calculateDuration($permit);
        if ($duration <= 0) {
            return;
        }

        $year = $permit->start_date?->year ?? now()->year;
        $quota = EmployeeLeaveQuota::where('employee_id', $permit->employee_id)
            ->where('year', $year)
            ->first();

        if (! $quota) {
            return;
        }

        $quota->withoutEvents(function () use ($quota, $duration) {
            $quota->decrement('used_quota', $duration);
            $quota->increment('remaining_quota', $duration);
        });
    }

    private function calculateDuration(Permit $permit): int
    {
        if (! $permit->start_date || ! $permit->end_date) {
            return 0;
        }

        return $permit->start_date->diffInDays($permit->end_date) + 1;
    }

    private function findOrCreateQuota(int $employeeId, int $year, ?int $companyId): EmployeeLeaveQuota
    {
        $quota = EmployeeLeaveQuota::where('employee_id', $employeeId)
            ->where('year', $year)
            ->first();

        if (! $quota) {
            try {
                $quota = EmployeeLeaveQuota::create([
                    'employee_id' => $employeeId,
                    'year' => $year,
                    'total_quota' => 12,
                    'used_quota' => 0,
                    'remaining_quota' => 12,
                    'company_id' => $companyId,
                ]);
            } catch (QueryException $e) {
                // Unique constraint violation (race condition) — re-fetch
                $quota = EmployeeLeaveQuota::where('employee_id', $employeeId)
                    ->where('year', $year)
                    ->first();
            }
        }

        return $quota;
    }
}
