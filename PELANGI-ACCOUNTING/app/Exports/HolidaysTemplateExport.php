<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class HolidaysTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['Eid al-Fitr', '2025-03-30', 'yes'],
            ['Indonesian Independence Day', '2025-08-17', 'no'],
            ['New Year', '2025-01-01', 'no'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'date',
            'is_cuti_bersama',
        ];
    }

    public function title(): string
    {
        return 'Holidays Import Template';
    }
}
