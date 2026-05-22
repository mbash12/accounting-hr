<?php

namespace App\Exports;

use App\Models\ProductGroup;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductGroupsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = ProductGroup::select(['name', 'shipping_type', 'is_active']);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($productGroup) {
                return [
                    'product_group_name' => $productGroup->name,
                    'shipping_type' => $productGroup->shipping_type,
                    'active_status' => $productGroup->is_active ? 'Yes' : 'No',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Product Group Name',
            'Shipping Type',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Product Group Data';
    }
}
