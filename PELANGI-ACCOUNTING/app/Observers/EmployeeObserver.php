<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Database\QueryException;

class EmployeeObserver
{
    /**
     * Auto-create a leave quota record for the current year
     * whenever a new Employee is created.
     */
    public function created(Employee $employee): void
    {
        $year = (int) now()->year;

        // Avoid creating duplicate (in case of race condition)
        $exists = EmployeeLeaveQuota::where('employee_id', $employee->id)
            ->where('year', $year)
            ->exists();

        if ($exists) {
            return;
        }

        try {
            EmployeeLeaveQuota::create([
                'employee_id' => $employee->id,
                'year' => $year,
                'total_quota' => 12,
                'used_quota' => 0,
                'remaining_quota' => 12,
                'company_id' => $employee->company_id,
            ]);
        } catch (QueryException $e) {
            // Unique constraint violation (race condition) — safe to ignore
        }
    }
}
