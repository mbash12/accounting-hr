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
            ['TJB',  'Tunjangan Jabatan',             'allowance', 'yes', 'yes', 'yes', 'yes'],
            ['TJM',  'Tunjangan Makan',                'allowance', 'no',  'no',  'no',  'yes'],
            ['TJT',  'Tunjangan Transportasi',         'allowance', 'yes', 'no',  'no',  'yes'],
            ['BPJSK','BPJS Kesehatan Potongan',        'deduction', 'yes', 'no',  'no',  'yes'],
            ['BPJTK','BPJS Ketenagakerjaan Potongan',  'deduction', 'yes', 'no',  'no',  'yes'],
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
            'is_active',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Komponen Gaji';
    }
}
