<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaxTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        // Return sample data as template with real account codes from the CSV
        return collect([
            [
                'VAT 11%',
                'PPN-11',
                11,
                'vat',
                'Yes',
                'Yes',
                'Yes',
                '51000100', // Purchase VAT (expense account from CSV)
                '40000100', // Sales (revenue account from CSV)
            ],
            [
                'Income Tax 23',
                'PPh-23',
                2,
                'withholding_tax',
                'No',
                'Yes',
                'Yes',
                '',
                '40000100',   // Sales (revenue account from CSV)
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'tax_name',
            'tax_code',
            'tax_percentage',
            'tax_type',
            'purchase_tax',
            'sales_tax',
            'active_status',
            'purchase_account',
            'sales_account',
        ];
    }

    public function title(): string
    {
        return 'Tax Import Template';
    }
}
