<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseOrderWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PO-001',
                '2024-01-01',
                'REF-001',
                'Pesanan pertama dari pemasok A',
                50000,
                100000,
                5,
                2000000,
                200000,
                2150000,
                'draft',
                'SUP-001',
                'PT. Pemasok A',
                'DEPT-001',
                'Departemen Produksi',
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
                'PO-002',
                '2024-01-02',
                'REF-002',
                'Pesanan dari pemasok B',
                0,
                50000,
                0,
                1500000,
                75000,
                1525000,
                'posted',
                'SUP-002',
                'CV. Pemasok B',
                'DEPT-002',
                'Departemen Gudang',
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
                'PO-003',
                '2024-01-03',
                'REF-003',
                'Pesanan dari pemasok C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'SUP-003',
                'PT. Pemasok C',
                'DEPT-003',
                'Departemen IT',
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
            'No. Pesanan Pembelian',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Diskon Persen',
            'Subtotal',
            'Pajak',
            'Total',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
            'Kode Departemen',
            'Nama Departemen',
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
        return 'Template Impor Pesanan Pembelian dan Item';
    }
}