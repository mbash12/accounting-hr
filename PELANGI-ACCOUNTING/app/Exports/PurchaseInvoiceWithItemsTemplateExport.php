<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseInvoiceWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PI-001',
                '2024-01-01',
                '2024-01-31',
                'REF-001',
                'First invoice from supplier A',
                50000,
                100000,
                5,
                2000000,
                200000,
                2150000,
                0,
                2150000,
                'draft',
                'SUP-001',
                'PT. Supplier A',
                'PO-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                2,
                1000000,
                2000000,
                'PCS',
                'PPN'
            ],
            [
                'PI-002',
                '2024-01-02',
                '2024-02-01',
                'REF-002',
                'Invoice from supplier B',
                0,
                50000,
                0,
                1500000,
                75000,
                1525000,
                1525000,
                0,
                'posted',
                'SUP-002',
                'CV. Supplier B',
                'PO-002',
                'PROD-002',
                'Wireless Mouse',
                'Wireless mouse with bluetooth technology',
                5,
                200000,
                1000000,
                'PCS',
                ''
            ],
            [
                'PI-003',
                '2024-01-03',
                '2024-02-02',
                'REF-003',
                'Invoice from supplier C',
                0,
                0,
                0,
                5000000,
                0,
                5000000,
                0,
                5000000,
                'draft',
                'SUP-003',
                'PT. Supplier C',
                'PO-003',
                'PROD-003',
                'Mechanical Keyboard',
                'Mechanical keyboard with RGB',
                10,
                500000,
                5000000,
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
            'Due Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Discount %',
            'Subtotal',
            'Tax',
            'Total',
            'Paid Amount',
            'Outstanding Amount',
            'Status',
            'Supplier Code',
            'Supplier Name',
            'Purchase Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Unit Code',
            'Tax Code',
        ];
    }

    public function title(): string
    {
        return 'Purchase Invoice Import Template';
    }
}
