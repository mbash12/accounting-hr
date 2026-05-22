<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FixedAssetTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Toyota Avanza Company Car',
                'AST-001',
                'VEHICLES',
                '2023-01-15',
                'Fixed asset for operational transportation',
                '25000000',
                '5',
                'Yes'
            ],
            [
                'Production Machine Type A',
                'AST-002',
                'MACHINERY',
                '2022-06-10',
                'Production machine for food products',
                '500000000',
                '10',
                'Yes'
            ],
            [
                'Dell Inspiron Laptop',
                'AST-003',
                'COMPUTER',
                '2023-07-05',
                'Laptop for administrative staff',
                '12000000',
                '3',
                'Yes'
            ],
            [
                'Ergonomic Office Chair',
                'AST-004',
                'EQUIPMENT',
                '2021-12-01',
                'Chair for office staff',
                '2500000',
                '5',
                'Yes'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'category_code',
            'acquisition_date',
            'description',
            'acquisition_value',
            'useful_life',
            'is_active'
        ];
    }

    public function title(): string
    {
        return 'Fixed Asset Import Template';
    }
}
