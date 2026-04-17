<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalaryComponentsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['', 'Tunjangan Makan', 'allowance', 'yes', 'no', 'no', 'yes'],
            ['', 'Tunjangan Transport', 'allowance', 'yes', 'no', 'no', 'yes'],
            ['', 'Potongan BPJS Kesehatan', 'deduction', 'no', 'no', 'yes', 'yes'],
        ];
    }

    public function headings(): array
    {
        return [
            'code',
            'name',
            'type',
            'is_fixed',
            'is_taxable',
            'is_bpjs_base',
            'active_status',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Komponen Gaji';
    }
}
