<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class JournalEntryTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'No Entry',
                '2026-06-05',
                'REF-001',
                'Pembelian barang dari supplier',
                '11000200',
                'Piutang Dagang',
                1000000,
                '',
                'Debit piutang',
            ],
            [
                'No Entry',
                '2026-06-05',
                'REF-001',
                'Pembelian barang dari supplier',
                '40000100',
                'Penjualan',
                '',
                1000000,
                'Kredit penjualan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'No Entry',
            'Tanggal',
            'Referensi',
            'Deskripsi',
            'Kode Akun',
            'Nama Akun',
            'Debit',
            'Kredit',
            'Catatan',
        ];
    }

    public function title(): string
    {
        return 'Journal Entry Import Template';
    }
}
