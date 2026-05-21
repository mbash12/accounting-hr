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
                    'Tax Code' => $tax->code,
                    'Tax Name' => $tax->name,
                    'Tax Percentage' => $tax->tax_percentage,
                    'Tax Type' => $tax->tax_type,
                    'Purchase Tax' => $tax->is_purchase_tax ? 'Yes' : 'No',
                    'Sales Tax' => $tax->is_sales_tax ? 'Yes' : 'No',
                    // 'Effective Date' => $tax->effective_date,
                    // 'Expiry Date' => $tax->expiry_date,
                    // 'Compound Tax' => $tax->compound_tax ? 'Yes' : 'No',
                    'Purchase Account' => $tax->purchaseAccount ? $tax->purchaseAccount->code : null,
                    'Sales Account' => $tax->salesAccount ? $tax->salesAccount->code : null,
                    'Active Status' => $tax->is_active ? 'Yes' : 'No',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tax Code',
            'Tax Name',
            'Tax Percentage',
            'Tax Type',
            'Purchase Tax',
            'Sales Tax',
            // 'Effective Date',
            // 'Expiry Date',
            // 'Compound Tax',
            'Purchase Account',
            'Sales Account',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Tax Data';
    }
}
