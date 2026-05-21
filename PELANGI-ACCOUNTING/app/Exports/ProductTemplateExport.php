<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Dell Inspiron Laptop',
                'LP-001',
                'Dell Inspiron 15 inch laptop with Intel Core i5 processor, 8GB RAM, 256GB SSD',
                12000000,
                15000000,
                'good',
                'yes',
                'Pcs',
                'ELK',
                'PPN',
            ],
            [
                'Logitech Wireless Mouse',
                'MO-001',
                'Logitech wireless mouse with high precision optical sensor',
                150000,
                200000,
                'good',
                'yes',
                'Pcs',
                'ELK',
                'PPN',
            ],
            [
                'Premium Accounting Software',
                'SA-001',
                'Accounting software for small and medium businesses',
                500000,
                750000,
                'service',
                'yes',
                'Pcs',
                'SW',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'description',
            'cost_price',
            'selling_price',
            'product_type',
            'is_active',
            'unit_code',
            'product_group_code',
            'tax_code',
        ];
    }

    public function title(): string
    {
        return 'Product Import Template';
    }
}
