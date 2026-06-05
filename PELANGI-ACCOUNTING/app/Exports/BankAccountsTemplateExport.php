<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BankAccountsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'BCA',
                '1234567890',
                'Rekening BCA Utama',
                'yes',
            ],
            [
                'MANDIRI',
                '9876543210',
                'Rekening Mandiri Operasional',
                'yes',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'bank_code',
            'account_number',
            'account_name',
            'active_status',
        ];
    }

    public function title(): string
    {
        return 'Bank Account Import Template';
    }
}
