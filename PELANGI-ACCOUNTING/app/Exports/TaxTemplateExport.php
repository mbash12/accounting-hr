<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaxTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        // Return sample data as template with real account codes from the CSV
        return collect([
            [
                'PPN 11%',
                'PPN-11',
                11,
                'vat',
                'ya',
                'ya',
                'ya',
                '51000100', // Pembelian Barang PPN (expense account from CSV)
                '40000100', // Penjualan (revenue account from CSV)
            ],
            [
                'PPh 23',
                'PPh-23',
                2,
                'withholding_tax',
                'tidak',
                'ya',
                'ya',
                '',
                '40000100',   // Penjualan (revenue account from CSV)
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_pajak',
            'kode_pajak',
            'persentase_pajak',
            'jenis_pajak',
            'pajak_pembelian',
            'pajak_penjualan',
            'status_aktif',
            'akun_pembelian',
            'akun_penjualan'
        ];
    }

    public function title(): string
    {
        return 'Template Data Pajak';
    }
}