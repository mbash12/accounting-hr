<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesOrderWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'SO-001',
                '2024-01-01',
                'standar',
                'REF-001',
                'Pesanan pertama dari pelanggan A',
                10,
                50000,
                100000,
                1800000,
                180000,
                1930000,
                'draft',
                'CUST-001',
                'PT. Pelanggan A',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                2,
                1000000,
                2000000,
                0,
                0,
                0,
                'PCS',
                'PPN'
            ],
            [
                'SO-002',
                '2024-01-02',
                'deposit',
                'REF-002',
                'Pesanan deposit dari pelanggan B',
                5,
                0,
                50000,
                1500000,
                75000,
                1525000,
                'draft',
                'CUST-002',
                'CV. Pelanggan B',
                'PROD-002',
                'Mouse Wireless',
                'Mouse wireless dengan teknologi bluetooth',
                5,
                200000,
                1000000,
                0,
                0,
                0,
                'PCS',
                ''
            ],
            [
                'SO-003',
                '2024-01-03',
                'aktual',
                'REF-003',
                'Pesanan aktual dari pelanggan C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                'posted',
                'CUST-003',
                'PT. Pelanggan C',
                'PROD-003',
                'Keyboard Mechanical',
                'Keyboard mekanik dengan RGB',
                10,
                500000,
                5000000,
                0,
                0,
                0,
                'PCS',
                'PPN'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Pesanan',
            'Tanggal',
            'Tipe Pesanan',
            'Referensi',
            'Deskripsi Pesanan',
            'Diskon Persen',
            'Biaya Lainnya',
            'Diskon',
            'Subtotal',
            'Pajak',
            'Total',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Harga Satuan',
            'Total Item',
            'Diskon Item',
            'Diskon Persen Item',
            'Pajak Item',
            'Kode Satuan',
            'Kode Pajak',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Pesanan Penjualan dan Item';
    }
}