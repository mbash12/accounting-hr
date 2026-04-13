<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseInvoiceWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PI-001',
                '2024-01-01',
                '2024-01-31',
                'REF-001',
                'Faktur pertama dari pemasok A',
                50000,
                100000,
                5,
                2000000,
                200000,
                2150000,
                0,
                2150000,
                'draft',
                'SUP-001',
                'PT. Pemasok A',
                'PO-001',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                2,
                1000000,
                2000000,
                'PCS',
                'PPN'
            ],
            [
                'PI-002',
                '2024-01-02',
                '2024-02-01',
                'REF-002',
                'Faktur dari pemasok B',
                0,
                50000,
                0,
                1500000,
                75000,
                1525000,
                1525000,
                0,
                'posted',
                'SUP-002',
                'CV. Pemasok B',
                'PO-002',
                'PROD-002',
                'Mouse Wireless',
                'Mouse wireless dengan teknologi bluetooth',
                5,
                200000,
                1000000,
                'PCS',
                ''
            ],
            [
                'PI-003',
                '2024-01-03',
                '2024-02-02',
                'REF-003',
                'Faktur dari pemasok C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'SUP-003',
                'PT. Pemasok C',
                'PO-003',
                'PROD-003',
                'Keyboard Mechanical',
                'Keyboard mekanik dengan RGB',
                10,
                500000,
                5000000,
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
            'Tanggal Jatuh Tempo',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Diskon Persen',
            'Subtotal',
            'Pajak',
            'Total',
            'Jumlah Dibayar',
            'Jumlah Terhutang',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Pesanan Pembelian',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Harga Satuan',
            'Total Item',
            'Kode Satuan',
            'Kode Pajak',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Faktur Pembelian dan Item';
    }
}