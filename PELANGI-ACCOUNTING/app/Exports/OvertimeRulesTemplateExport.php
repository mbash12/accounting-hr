<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OvertimeRulesTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['Aturan Lembur Standar', '', 'yes', 173, 1.5, 2, 2, 'yes'],
            ['Aturan Lembur IT', 'IT', 'no', 173, 1.5, 2, 2.5, 'yes'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'department_code',
            'is_default',
            'base_hourly_rate_divisor',
            'workday_first_hour_multiplier',
            'workday_subsequent_hour_multiplier',
            'holiday_multiplier',
            'active_status',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Aturan Lembur';
    }
}
