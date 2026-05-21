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
                    'Kode Produk' => $product->code,
                    'Nama Produk' => $product->name,
                    'Deskripsi' => $product->description,
                    'Harga Beli' => $product->cost_price,
                    'Harga Jual' => $product->selling_price,
                    'Tipe Produk' => $product->product_type,
                    'Kode Satuan' => $product->unit ? $product->unit->code : null,
                    'Kode Grup Produk' => $product->productGroup ? $product->productGroup->code : null,
                    'Kode Pajak' => $product->tax ? $product->tax->code : null,
                    'Status Aktif' => $product->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Deskripsi',
            'Harga Beli',
            'Harga Jual',
            'Tipe Produk',
            'Kode Satuan',
            'Kode Grup Produk',
            'Kode Pajak',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Produk';
    }
}