<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseReturnWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PR-001',
                '2024-01-01',
                'REF-001',
                'Retur pertama dari pemasok A',
                'draft',
                'SUP-001',
                'PT. Pemasok A',
                'GR-001',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                1,
                'Rusak',
                'PCS'
            ],
            [
                'PR-002',
                '2024-01-02',
                'REF-002',
                'Retur dari pemasok B',
                'posted',
                'SUP-002',
                'CV. Pemasok B',
                'GR-002',
                'PROD-002',
                'Mouse Wireless',
                'Mouse wireless dengan teknologi bluetooth',
                2,
                'Salah barang',
                'PCS'
            ],
            [
                'PR-003',
                '2024-01-03',
                'REF-003',
                'Retur dari pemasok C',
                'draft',
                'SUP-003',
                'PT. Pemasok C',
                'GR-003',
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
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Penerimaan Barang',
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
        return 'Template Impor Retur Pembelian dan Item';
    }
}