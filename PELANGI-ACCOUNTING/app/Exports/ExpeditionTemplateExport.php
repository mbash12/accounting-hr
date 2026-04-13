<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExpeditionTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'JNE Express',
                'JNE-001',
                'yes',
            ],
            [
                'TIKI',
                'TIKI-002',
                'yes',
            ],
            [
                'POS Indonesia',
                'POS-003',
                'no',
            ],
            [
                'J&T Express',
                'JNT-004',
                'yes',
            ],
            [
                'SiCepat Express',
                'SICEPAT-005',
                'yes',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'is_active'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Ekspedisi';
    }
}