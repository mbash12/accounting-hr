<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReturnWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'RET-001',
                '2024-01-01',
                'REF-001',
                'Retur pertama dari pelanggan A',
                'draft',
                'CUST-001',
                'PT. Pelanggan A',
                'DEL-001',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                1,
                'Rusak',
                'PCS'
            ],
            [
                'RET-002',
                '2024-01-02',
                'REF-002',
                'Retur dari pelanggan B',
                'posted',
                'CUST-002',
                'CV. Pelanggan B',
                'DEL-002',
                'PROD-002',
                'Mouse Wireless',
                'Mouse wireless dengan teknologi bluetooth',
                2,
                'Salah barang',
                'PCS'
            ],
            [
                'RET-003',
                '2024-01-03',
                'REF-003',
                'Retur dari pelanggan C',
                'draft',
                'CUST-003',
                'PT. Pelanggan C',
                'DEL-003',
                'PROD-003',
                'Keyboard Mechanical',
                'Keyboard mekanik dengan RGB',
                1,
                'Tidak dibutuhkan',
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Retur',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pengiriman',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Alasan Retur',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Retur Penjualan dan Item';
    }
}