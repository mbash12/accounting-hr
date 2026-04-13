<?php

namespace App\Exports;

use App\Models\FixedAsset;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FixedAssetExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = FixedAsset::select([
                'name', 'code', 'location', 'acquisition_date', 'description',
                'acquisition_value', 'monthly_depreciation', 'depreciation_method',
                'accumulated_depreciation', 'useful_life', 'book_value', 'is_active',
                'category_id', 'department_id'
            ])
            ->with([
                'category', 'department'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($asset) {
                return [
                    'code' => $asset->code,
                    'name' => $asset->name,
                    'acquisition_date' => $asset->acquisition_date ? $asset->acquisition_date->format('Y-m-d') : '',
                    'description' => $asset->description,
                    'acquisition_value' => $asset->acquisition_value,
                    // 'monthly_depreciation' => $asset->monthly_depreciation,
                    // 'depreciation_method' => $asset->depreciation_method,
                    // 'accumulated_depreciation' => $asset->accumulated_depreciation,
                    'useful_life' => $asset->useful_life,
                    'book_value' => $asset->book_value,
                    'category_code' => $asset->category?->code ?? '',
                    // 'department_code' => $asset->department?->code ?? '',
                    'is_active' => $asset->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama Aset Tetap',
            'Tanggal Perolehan',
            'Deskripsi',
            'Nilai Perolehan',
            // 'Penyusutan Bulanan',
            // 'Metode Penyusutan',
            // 'Akumulasi Penyusutan',
            'Masa Manfaat',
            'Nilai Buku',
            'Kode Kategori',
            // 'Kode Departemen',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Aset Tetap';
    }
}