<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'HR',
                'Human Resources',
                'yes'
            ],
            [
                'IT',
                'Information Technology',
                'yes'
            ],
            [
                'FIN',
                'Finance',
                'yes'
            ],
            [
                'OPS',
                'Operations',
                'yes'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'department_code',
            'department_name',
            'active_status'
        ];
    }

    public function title(): string
    {
        return 'Department Import Template';
    }
}
