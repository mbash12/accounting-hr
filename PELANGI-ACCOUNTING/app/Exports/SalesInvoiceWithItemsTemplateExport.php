<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesInvoiceWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'INV-001',
                '2024-01-01',
                'REF-001',
                'Faktur pertama dari pelanggan A',
                50000,
                100000,
                2000000,
                200000,
                2150000,
                0,
                2150000,
                'draft',
                'CUST-001',
                'PT. Pelanggan A',
                'SO-001',
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
                'INV-002',
                '2024-01-02',
                'REF-002',
                'Faktur dari pelanggan B',
                0,
                50000,
                1500000,
                75000,
                1525000,
                1525000,
                0,
                'posted',
                'CUST-002',
                'CV. Pelanggan B',
                'SO-002',
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
                'INV-003',
                '2024-01-03',
                'REF-003',
                'Faktur dari pelanggan C',
                0,
                0,
                5000000,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'CUST-003',
                'PT. Pelanggan C',
                'SO-003',
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
            'Nomor Faktur',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Subtotal',
            'Pajak',
            'Total',
            'Jumlah Dibayar',
            'Jumlah Terhutang',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pesanan Penjualan',
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
        return 'Template Impor Faktur Penjualan dan Item';
    }
}