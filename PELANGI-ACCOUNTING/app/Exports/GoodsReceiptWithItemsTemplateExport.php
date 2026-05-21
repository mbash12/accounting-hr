<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GoodsReceiptWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'GR-001',
                '2024-01-01',
                'REF-001',
                'First receipt from supplier A',
                'draft',
                'SUP-001',
                'PT. Supplier A',
                'PO-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                2,
                'PCS'
            ],
            [
                'GR-002',
                '2024-01-02',
                'REF-002',
                'Receipt from supplier B',
                'posted',
                'SUP-002',
                'CV. Supplier B',
                'PO-002',
                'PROD-002',
                'Wireless Mouse',
                'Wireless mouse with bluetooth technology',
                5,
                'PCS'
            ],
            [
                'GR-003',
                '2024-01-03',
                'REF-003',
                'Receipt from supplier C',
                'draft',
                'SUP-003',
                'PT. Supplier C',
                'PO-003',
                'PROD-003',
                'Mechanical Keyboard',
                'Mechanical keyboard with RGB',
                10,
                'PCS'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Receipt No.',
            'Date',
            'Reference No.',
            'Description',
            'Status',
            'Supplier Code',
            'Supplier Name',
            'Purchase Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Goods Receipt Import Template';
    }
}
