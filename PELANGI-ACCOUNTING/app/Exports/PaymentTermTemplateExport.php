<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentTermTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        // Return sample data as template
        return collect([
            [
                'CASH',
                'Cash / Tunai',
                0,
                'ya',
                'Pembayaran tunai saat transaksi',
            ],
            [
                'NET15',
                'Net 15',
                15,
                'ya',
                'Pembayaran jatuh tempo 15 hari',
            ],
            [
                'NET30',
                'Net 30',
                30,
                'ya',
                'Pembayaran jatuh tempo 30 hari',
            ],
            [
                'NET45',
                'Net 45',
                45,
                'ya',
                'Pembayaran jatuh tempo 45 hari',
            ],
            [
                'NET60',
                'Net 60',
                60,
                'tidak',
                'Pembayaran jatuh tempo 60 hari',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'kode_termin',
            'nama_termin',
            'jumlah_hari',
            'status_aktif',
            'deskripsi',
        ];
    }

    public function title(): string
    {
        return 'Template Data Termin Pembayaran';
    }
}
