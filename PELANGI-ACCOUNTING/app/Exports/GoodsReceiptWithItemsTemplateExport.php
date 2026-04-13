<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GoodsReceiptWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'GR-001',
                '2024-01-01',
                'REF-001',
                'Penerimaan pertama dari pemasok A',
                'draft',
                'SUP-001',
                'PT. Pemasok A',
                'PO-001',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                2,
                'PCS'
            ],
            [
                'GR-002',
                '2024-01-02',
                'REF-002',
                'Penerimaan dari pemasok B',
                'posted',
                'SUP-002',
                'CV. Pemasok B',
                'PO-002',
                'PROD-002',
                'Mouse Wireless',
                'Mouse wireless dengan teknologi bluetooth',
                5,
                'PCS'
            ],
            [
                'GR-003',
                '2024-01-03',
                'REF-003',
                'Penerimaan dari pemasok C',
                'draft',
                'SUP-003',
                'PT. Pemasok C',
                'PO-003',
                'PROD-003',
                'Keyboard Mechanical',
                'Keyboard mekanik dengan RGB',
                10,
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'No. Penerimaan Barang',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Pesanan Pembelian',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Penerimaan Barang dan Item';
    }
}