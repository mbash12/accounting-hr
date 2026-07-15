<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Console\Command;

class RolloverLeaveQuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *   php artisan leave-quota:rollover             # roll over to current year
     *   php artisan leave-quota:rollover --year=2026 # roll over to specific year
     *   php artisan leave-quota:rollover --company=1 # only for specific company
     */
    protected $signature = 'leave-quota:rollover
        {--year= : Target year (default: current year)}
        {--company= : Only process this company ID}
        {--default-quota=12 : Default total quota for new records}';

    /**
     * The console command description.
     */
    protected $description = 'Generate leave quota records for the given year for all active employees.';

    public function handle(): int
    {
        $year = (int) ($this->option('year') ?? now()->year);
        $companyId = $this->option('company') ? (int) $this->option('company') : null;
        $defaultQuota = (int) $this->option('default-quota');

        $this->info("Rollover leave quotas for year {$year}...");

        $query = Employee::where('is_active', true);
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $employees = $query->get();
        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            $exists = EmployeeLeaveQuota::where('employee_id', $employee->id)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            EmployeeLeaveQuota::create([
                'employee_id' => $employee->id,
                'year' => $year,
                'total_quota' => $defaultQuota,
                'used_quota' => 0,
                'remaining_quota' => $defaultQuota,
                'company_id' => $employee->company_id,
            ]);
            $created++;
        }

        $this->info("Done. Created: {$created}, Skipped (already exists): {$skipped}, Total employees: {$employees->count()}");

        return self::SUCCESS;
    }
}
