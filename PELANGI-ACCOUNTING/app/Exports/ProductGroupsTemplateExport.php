<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductGroupsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Elektronik',
                'ELK',
                'physical',
                'yes'
            ],
            [
                'Perangkat Lunak',
                'SW',
                'digital',
                'yes'
            ],
            [
                'Buku',
                'BKS',
                'physical',
                'yes'
            ],
            [
                'Kursus Daring',
                'KUR',
                'digital',
                'yes'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_grup_produk',
            'kode_grup_produk',
            'tipe_pengiriman',
            'status_aktif'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Grup Produk';
    }
}