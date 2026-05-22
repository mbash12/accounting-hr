<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseReturnWithItemsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'PR-001',
                '2024-01-01',
                'REF-001',
                'First return from supplier A',
                'draft',
                'SUP-001',
                'PT. Supplier A',
                'GR-001',
                'PROD-001',
                'Gaming Laptop',
                'Gaming laptop with high specifications',
                1,
                'Damaged',
                'PCS'
            ],
            [
                'PR-002',
                '2024-01-02',
                'REF-002',
                'Return from supplier B',
                'posted',
                'SUP-002',
                'CV. Supplier B',
                'GR-002',
                'PROD-002',
                'Wireless Mouse',
                'Wireless mouse with bluetooth technology',
                2,
                'Wrong item',
                'PCS'
            ],
            [
                'PR-003',
                '2024-01-03',
                'REF-003',
                'Return from supplier C',
                'draft',
                'SUP-003',
                'PT. Supplier C',
                'GR-003',
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
            'Supplier Code',
            'Supplier Name',
            'Goods Receipt No.',
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
        return 'Purchase Return Import Template';
    }
}
