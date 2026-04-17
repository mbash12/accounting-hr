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
            ['Hari Raya Idul Fitri', '2025-03-30', 'yes'],
            ['Hari Kemerdekaan RI', '2025-08-17', 'no'],
            ['Tahun Baru Masehi', '2025-01-01', 'no'],
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
        return 'Template Impor Hari Libur';
    }
}
