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
                    'nama_grup_produk' => $productGroup->name,
                    'tipe_pengiriman' => $productGroup->shipping_type,
                    'status_aktif' => $productGroup->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Grup Produk',
            'Tipe Pengiriman',
            'Status Aktif'
        ];
    }

    public function title(): string
    {
        return 'Data Grup Produk';
    }
}