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
                'Standard international weight unit',
                'yes'
            ],
            [
                'GR',
                'Gram',
                'Small weight unit',
                'yes'
            ],
            [
                'M',
                'Meter',
                'Length unit',
                'yes'
            ],
            [
                'CM',
                'Centimeter',
                'Small length unit',
                'yes'
            ],
            [
                'L',
                'Liter',
                'Volume unit',
                'yes'
            ],
            [
                'ML',
                'Milliliter',
                'Small volume unit',
                'yes'
            ],
            [
                'Pcs',
                'Pieces',
                'Pieces unit',
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
        return 'Unit Measurement Import Template';
    }
}
