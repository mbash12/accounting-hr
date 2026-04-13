<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UnitMeasurementsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'KG',
                'Kilogram',
                'Unit berat standar internasional',
                'yes'
            ],
            [
                'GR',
                'Gram',
                'Unit berat kecil',
                'yes'
            ],
            [
                'M',
                'Meter',
                'Unit panjang',
                'yes'
            ],
            [
                'CM',
                'Centimeter',
                'Unit panjang kecil',
                'yes'
            ],
            [
                'L',
                'Liter',
                'Unit volume',
                'yes'
            ],
            [
                'ML',
                'Milliliter',
                'Unit volume kecil',
                'yes'
            ],
            [
                'Pcs',
                'Pieces',
                'Unit potong/pieces',
                'yes'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'unit_code',
            'unit_name',
            'unit_description',
            'active_status'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Satuan';
    }
}