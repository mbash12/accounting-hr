<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FixedAssetCategoryTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Kendaraan Operasional',
                'KENDARAAN',
                'straight_line',
                '5',
                'yes',
                '40000100',
                'Penjualan',
                '17000300',
                'Kendaraan',
                '17100200',
                'Akumulasi Penyusutan Kendaraan',
                '63000200',
                'Beban Penyusutan Kendaraan',
            ],
            [
                'Peralatan Kantor',
                'PERKANTORAN',
                'double_declining',
                '4',
                'yes',
                '40000100',
                'Penjualan',
                '17000400',
                'Peralatan Kantor Gol 1',
                '17100300',
                'Akumulasi Penyusutan Peralatan Kantor Gol I',
                '63000300',
                'Beban Penyusutan Peralatan',
            ],
            [
                'Gedung & Bangunan',
                'GEDUNG',
                'straight_line',
                '20',
                'yes',
                '40000100',
                'Penjualan',
                '17000200',
                'Gedung',
                '17100100',
                'Akumulasi Penyusutan Gedung',
                '63000100',
                'Beban Penyusutan Gedung',
            ],
            [
                'Mesin Produksi',
                'MESIN',
                'sum_of_years',
                '10',
                'no',
                '40000100',
                'Penjualan',
                '17000500',
                'Peralatan Kantor Gol II',
                '17100400',
                'Akumulasi Penyusutan Peralatan Kantor Gol II',
                '63000300',
                'Beban Penyusutan Peralatan',
            ],
            [
                'Komputer & IT',
                'KOMPUTER',
                'straight_line',
                '4',
                'yes',
                '40000100',
                'Penjualan',
                '17000400',
                'Peralatan Kantor Gol 1',
                '17100300',
                'Akumulasi Penyusutan Peralatan Kantor Gol I',
                '63000300',
                'Beban Penyusutan Peralatan',
            ],
            [
                'Peralatan Lainnya',
                'PERALATAN',
                'straight_line',
                '5',
                'yes',
                '40000100',
                'Penjualan',
                '17000400',
                'Peralatan Kantor Gol 1',
                '17100300',
                'Akumulasi Penyusutan Peralatan Kantor Gol I',
                '63000300',
                'Beban Penyusutan Peralatan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'depreciation_method',
            'useful_life',
            'is_active',
            'sales_account_code',
            'sales_account_name',
            'asset_account_code',
            'asset_account_name',
            'accumulated_depreciation_account_code',
            'accumulated_depreciation_account_name',
            'depreciation_account_code',
            'depreciation_account_name'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Kategori Aset Tetap';
    }
}