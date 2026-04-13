<?php

namespace App\Exports;

use App\Models\Tax;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaxExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Tax::with(['purchaseAccount', 'salesAccount'])
            ->select([
                'name', 'code', 'tax_percentage', 'tax_type', 'is_purchase_tax',
                'is_sales_tax', 'effective_date', 'expiry_date', 'compound_tax',
                'is_active', 'purchase_account_id', 'sales_account_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($tax) {
                return [
                    'Kode Pajak' => $tax->code,
                    'Nama Pajak' => $tax->name,
                    'Persentase Pajak' => $tax->tax_percentage,
                    'Jenis Pajak' => $tax->tax_type,
                    'Pajak Pembelian' => $tax->is_purchase_tax ? 'ya' : 'tidak',
                    'Pajak Penjualan' => $tax->is_sales_tax ? 'ya' : 'tidak',
                    // 'Tanggal Berlaku' => $tax->effective_date,
                    // 'Tanggal Kadaluarsa' => $tax->expiry_date,
                    // 'Pajak Majemuk' => $tax->compound_tax ? 'ya' : 'tidak',
                    'Akun Pembelian' => $tax->purchaseAccount ? $tax->purchaseAccount->code : null,
                    'Akun Penjualan' => $tax->salesAccount ? $tax->salesAccount->code : null,
                    'Status Aktif' => $tax->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Pajak',
            'Nama Pajak',
            'Persentase Pajak',
            'Jenis Pajak',
            'Pajak Pembelian',
            'Pajak Penjualan',
            // 'Tanggal Berlaku',
            // 'Tanggal Kadaluarsa',
            // 'Pajak Majemuk',
            'Akun Pembelian',
            'Akun Penjualan',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Pajak';
    }
}