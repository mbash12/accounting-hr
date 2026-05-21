<?php

namespace App\Exports;

use App\Models\Product;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Product::with(['unit', 'productGroup', 'tax'])
            ->select([
                'name', 'code', 'description', 'cost_price', 'selling_price',
                'product_type', 'is_active', 'unit_id', 'product_group_id', 'tax_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($product) {
                return [
                    'Product Code' => $product->code,
                    'Product Name' => $product->name,
                    'Description' => $product->description,
                    'Purchase Price' => $product->cost_price,
                    'Selling Price' => $product->selling_price,
                    'Product Type' => $product->product_type,
                    'Unit Code' => $product->unit ? $product->unit->code : null,
                    'Product Group Code' => $product->productGroup ? $product->productGroup->code : null,
                    'Tax Code' => $product->tax ? $product->tax->code : null,
                    'Active Status' => $product->is_active ? 'Yes' : 'No',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Product Code',
            'Product Name',
            'Description',
            'Purchase Price',
            'Selling Price',
            'Product Type',
            'Unit Code',
            'Product Group Code',
            'Tax Code',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Product Data';
    }
}
