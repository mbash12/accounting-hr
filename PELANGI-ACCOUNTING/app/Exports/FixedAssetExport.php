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
                    'is_active' => $asset->is_active ? 'Yes' : 'No',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Asset Code',
            'Fixed Asset Name',
            'Acquisition Date',
            'Description',
            'Acquisition Value',
            // 'Monthly Depreciation',
            // 'Depreciation Method',
            // 'Accumulated Depreciation',
            'Useful Life',
            'Book Value',
            'Category Code',
            // 'Department Code',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Fixed Asset Data';
    }
}
