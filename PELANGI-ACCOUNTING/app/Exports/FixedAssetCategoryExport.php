<?php

namespace App\Exports;

use App\Models\FixedAssetCategory;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FixedAssetCategoryExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = FixedAssetCategory::select([
                'name', 'depreciation_method', 'useful_life', 'is_active',
                'sales_account_id', 'asset_account_id', 'accumulated_depreciation_account_id',
                'depreciation_account_id'
            ])
            ->with([
                'salesAccount', 'assetAccount', 'accumulatedDepreciationAccount',
                'depreciationAccount'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'depreciation_method' => $category->depreciation_method,
                    'useful_life' => $category->useful_life,
                    'sales_account_code' => $category->salesAccount?->code ?? '',
                    'asset_account_code' => $category->assetAccount?->code ?? '',
                    'accumulated_depreciation_account_code' => $category->accumulatedDepreciationAccount?->code ?? '',
                    'depreciation_account_code' => $category->depreciationAccount?->code ?? '',
                    'is_active' => $category->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Kategori Aset Tetap',
            'Metode Penyusutan',
            'Masa Manfaat (Tahun)',
            'Kode Akun Penjualan',
            'Kode Akun Aset',
            'Kode Akun Akumulasi Penyusutan',
            'Kode Akun Penyusutan',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Kategori Aset Tetap';
    }
}