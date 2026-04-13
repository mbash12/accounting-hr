<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BanksTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'BCA',
                'Bank Central Asia',
                'Indonesia',
                '014',
                '101',
                'yes'
            ],
            [
                'BNI',
                'Bank Negara Indonesia',
                'Indonesia',
                '009',
                '102',
                'yes'
            ],
            [
                'BRI',
                'Bank Rakyat Indonesia',
                'Indonesia',
                '002',
                '103',
                'yes'
            ],
            [
                'MANDIRI',
                'Bank Mandiri',
                'Indonesia',
                '008',
                '104',
                'yes'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'bank_code',
            'bank_name',
            'country',
            'clearing_code',
            'skn_code',
            'active_status'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Bank';
    }
}