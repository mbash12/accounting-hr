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
                'Sumber Daya Manusia',
                'yes'
            ],
            [
                'IT',
                'Teknologi Informasi',
                'yes'
            ],
            [
                'FIN',
                'Keuangan',
                'yes'
            ],
            [
                'OPS',
                'Operasional',
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
        return 'Template Impor Departemen';
    }
}