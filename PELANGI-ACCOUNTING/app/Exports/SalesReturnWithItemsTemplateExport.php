<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReturnWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'RET-001',
                '2024-01-01',
                'REF-001',
                'First return from customer A',
                'draft',
                'CUST-001',
                'PT. Customer A',
                'DEL-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                1,
                'Damaged',
                'PCS'
            ],
            [
                'RET-002',
                '2024-01-02',
                'REF-002',
                'Return from customer B',
                'posted',
                'CUST-002',
                'CV. Customer B',
                'DEL-002',
                'PROD-002',
                'Wireless Mouse',
                'Wireless mouse with bluetooth technology',
                2,
                'Wrong item',
                'PCS'
            ],
            [
                'RET-003',
                '2024-01-03',
                'REF-003',
                'Return from customer C',
                'draft',
                'CUST-003',
                'PT. Customer C',
                'DEL-003',
                'PROD-003',
                'Mechanical Keyboard',
                'Mechanical keyboard with RGB',
                1,
                'Not needed',
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Return No.',
            'Date',
            'Reference No.',
            'Description',
            'Status',
            'Customer Code',
            'Customer Name',
            'Delivery No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Return Reason',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Sales Return Import Template';
    }
}
