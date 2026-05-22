<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeeLeaveQuotasTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['EMP-001', 2025, 12, 0],
            ['EMP-002', 2025, 12, 3],
            ['EMP-003', 2025, 12, 0],
        ];
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'year',
            'total_quota',
            'used_quota',
        ];
    }

    public function title(): string
    {
        return 'Employee Leave Quotas Import Template';
    }
}
