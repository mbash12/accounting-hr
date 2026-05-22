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
            ['TJB',  'Position Allowance',            'allowance', 'yes', 'yes', 'yes', 'yes'],
            ['TJM',  'Meal Allowance',                 'allowance', 'no',  'no',  'no',  'yes'],
            ['TJT',  'Transport Allowance',            'allowance', 'yes', 'no',  'no',  'yes'],
            ['BPJSK','BPJS Health Deduction',           'deduction', 'yes', 'no',  'no',  'yes'],
            ['BPJTK','BPJS Employment Deduction',       'deduction', 'yes', 'no',  'no',  'yes'],
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
        return 'Salary Components Import Template';
    }
}
