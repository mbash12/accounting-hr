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
                'Mobil Toyota Avanza',
                'AST-001',
                'KENDARAAN',
                '2023-01-15',
                'Aset Tetap untuk transportasi operasional',
                '25000000',
                '5',
                'Yes'
            ],
            [
                'Mesin Produksi Type A',
                'AST-002',
                'MESIN',
                '2022-06-10',
                'Mesin produksi untuk makanan',
                '500000000',
                '10',
                'Yes'
            ],
            [
                'Laptop Dell Inspiron',
                'AST-003',
                'KOMPUTER',
                '2023-07-05',
                'Laptop untuk staf administrasi',
                '12000000',
                '3',
                'Yes'
            ],
            [
                'Kursi Kantor Ergonomis',
                'AST-004',
                'PERALATAN',
                '2021-12-01',
                'Kursi untuk staf kantor',
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
        return 'Template Impor Aset Tetap';
    }
}