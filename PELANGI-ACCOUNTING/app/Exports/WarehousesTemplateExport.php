<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehousesTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'WH001',
                'Gudang Utama',
                'yes'
            ],
            [
                'WH002',
                'Gudang Bahan Baku',
                'yes'
            ],
            [
                'WH003',
                'Gudang Barang Jadi',
                'yes'
            ],
            [
                'WH004',
                'Gudang Sementara',
                'no'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kode_gudang',
            'nama_gudang',
            'status_aktif'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Gudang';
    }
}