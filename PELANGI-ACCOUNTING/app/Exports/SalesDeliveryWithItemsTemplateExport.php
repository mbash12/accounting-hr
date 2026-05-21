<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesDeliveryWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'DEL-001',
                '2024-01-01',
                'goods',
                'REF-001',
                'First delivery for customer A',
                'draft',
                'CUST-001',
                'PT. Customer A',
                'SO-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                2,
                'PCS'
            ],
            [
                'DEL-002',
                '2024-01-02',
                'document',
                'REF-002',
                'Document delivery for customer B',
                'posted',
                'CUST-002',
                'CV. Customer B',
                'SO-002',
                'PROD-002',
                'Contract Letter',
                'Partnership contract document',
                1,
                'SET'
            ],
            [
                'DEL-003',
                '2024-01-03',
                'goods',
                'REF-003',
                'Goods delivery for customer C',
                'posted',
                'CUST-003',
                'PT. Customer C',
                'SO-003',
                'PROD-003',
                'Mechanical Keyboard',
                'Mechanical keyboard with RGB',
                5,
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Delivery No.',
            'Date',
            'Delivery Type',
            'Reference No.',
            'Description',
            'Status',
            'Customer Code',
            'Customer Name',
            'Sales Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Sales Delivery Import Template';
    }
}
