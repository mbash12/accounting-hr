<?php

namespace App\Exports;

use App\Models\EmployeeLeaveQuota;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeeLeaveQuotasExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = EmployeeLeaveQuota::with('employee');

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()->map(function ($quota) {
            return [
                'employee_id' => $quota->employee?->employee_id,
                'year'        => $quota->year,
                'total_quota' => $quota->total_quota,
                'used_quota'  => $quota->used_quota,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Year',
            'Total Quota (Days)',
            'Used (Days)',
        ];
    }

    public function title(): string
    {
        return 'Employee Leave Quotas Data';
    }
}
