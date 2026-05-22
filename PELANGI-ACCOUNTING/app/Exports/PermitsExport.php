<?php

namespace App\Exports;

use App\Models\Permit;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PermitsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Permit::with('employee');

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->orderBy('start_date', 'desc')->get()->map(function ($permit) {
            return [
                'employee_id' => $permit->employee?->employee_id,
                'type'        => $permit->type,
                'start_date'  => $permit->start_date?->format('Y-m-d'),
                'end_date'    => $permit->end_date?->format('Y-m-d'),
                'reason'      => $permit->reason,
                'status'      => $permit->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Permit Type',
            'Start Date',
            'End Date',
            'Reason',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Permits & Leave Data';
    }
}
