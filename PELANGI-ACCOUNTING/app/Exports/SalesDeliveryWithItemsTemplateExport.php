<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesDeliveryWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'DEL-001',
                '2024-01-01',
                'goods',
                'REF-001',
                'Pengiriman pertama untuk pelanggan A',
                'draft',
                'CUST-001',
                'PT. Pelanggan A',
                'SO-001',
                'PROD-001',
                'Laptop Gaming',
                'Laptop gaming dengan spesifikasi tinggi',
                2,
                'PCS'
            ],
            [
                'DEL-002',
                '2024-01-02',
                'document',
                'REF-002',
                'Pengiriman dokumen untuk pelanggan B',
                'posted',
                'CUST-002',
                'CV. Pelanggan B',
                'SO-002',
                'PROD-002',
                'Surat Kontrak',
                'Dokumen kontrak kerja sama',
                1,
                'SET'
            ],
            [
                'DEL-003',
                '2024-01-03',
                'goods',
                'REF-003',
                'Pengiriman barang untuk pelanggan C',
                'posted',
                'CUST-003',
                'PT. Pelanggan C',
                'SO-003',
                'PROD-003',
                'Keyboard Mechanical',
                'Keyboard mekanik dengan RGB',
                5,
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Pengiriman',
            'Tanggal',
            'Jenis Pengiriman',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pesanan Penjualan',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Template Impor Pengiriman Penjualan dan Item';
    }
}