<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesOrderWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'SO-001',
                '2024-01-01',
                'REF-001',
                'First order from customer A',
                10,
                50000,
                100000,
                1800000,
                180000,
                1930000,
                'draft',
                'CUST-001',
                'PT. Customer A',
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
                'SO-002',
                '2024-01-02',
                'REF-002',
                'Deposit order from customer B',
                5,
                0,
                50000,
                1500000,
                75000,
                1525000,
                'draft',
                'CUST-002',
                'CV. Customer B',
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
                'SO-003',
                '2024-01-03',
                'REF-003',
                'Actual order from customer C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                'posted',
                'CUST-003',
                'PT. Customer C',
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
            'Order No.',
            'Date',
            'Reference',
            'Order Description',
            'Discount Pct.',
            'Other Charges',
            'Discount',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Customer Code',
            'Customer Name',
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
        return 'Sales Order Import Template';
    }
}
