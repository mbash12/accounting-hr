<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesInvoiceWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'INV-001',
                '2024-01-01',
                'REF-001',
                'First invoice for customer A',
                50000,
                100000,
                2000000,
                200000,
                2150000,
                0,
                2150000,
                'draft',
                'CUST-001',
                'PT. Customer A',
                'SO-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                2,
                1000000,
                2000000,
                0,
                0,
                0,
                'PCS',
                'PPN'
            ],
            [
                'INV-002',
                '2024-01-02',
                'REF-002',
                'Invoice from customer B',
                0,
                50000,
                1500000,
                75000,
                1525000,
                1525000,
                0,
                'posted',
                'CUST-002',
                'CV. Customer B',
                'SO-002',
                'PROD-002',
                'Wireless Mouse',
                'Wireless mouse with bluetooth technology',
                5,
                200000,
                1000000,
                0,
                0,
                0,
                'PCS',
                ''
            ],
            [
                'INV-003',
                '2024-01-03',
                'REF-003',
                'Invoice from customer C',
                0,
                0,
                5000000,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'CUST-003',
                'PT. Customer C',
                'SO-003',
                'PROD-003',
                'Mechanical Keyboard',
                'Mechanical keyboard with RGB',
                10,
                500000,
                5000000,
                0,
                0,
                0,
                'PCS',
                'PPN'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice No.',
            'Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Subtotal',
            'Tax',
            'Total',
            'Paid Amount',
            'Outstanding Amount',
            'Status',
            'Customer Code',
            'Customer Name',
            'Sales Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Item Discount',
            'Item Discount Pct.',
            'Item Tax',
            'Unit Code',
            'Tax Code',
        ];
    }

    public function title(): string
    {
        return 'Sales Invoice Import Template';
    }
}
