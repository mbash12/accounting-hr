<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseOrderWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PO-001',
                '2024-01-01',
                'REF-001',
                'First order from supplier A',
                50000,
                100000,
                5,
                2000000,
                200000,
                2150000,
                'draft',
                'SUP-001',
                'PT. Supplier A',
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
                'PO-002',
                '2024-01-02',
                'REF-002',
                'Order from supplier B',
                0,
                50000,
                0,
                1500000,
                75000,
                1525000,
                'posted',
                'SUP-002',
                'CV. Supplier B',
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
                'PO-003',
                '2024-01-03',
                'REF-003',
                'Order from supplier C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'SUP-003',
                'PT. Supplier C',
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
            'Purchase Order No.',
            'Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Discount Pct.',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Supplier Code',
            'Supplier Name',
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
        return 'Purchase Order Import Template';
    }
}
