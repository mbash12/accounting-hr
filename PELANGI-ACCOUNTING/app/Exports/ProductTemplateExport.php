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
                'Laptop Dell Inspiron',
                'LP-001',
                'Laptop Dell Inspiron 15 inch dengan prosesor Intel Core i5, RAM 8GB, SSD 256GB',
                12000000,
                15000000,
                'good',
                1,
                'yes',
                'Pcs',
                'ELK',
                'PPN',
                'SUP-001'
            ],
            [
                'Mouse Wireless Logitech',
                'MO-001',
                'Mouse wireless Logitech dengan sensor optik presisi tinggi',
                150000,
                200000,
                'good',
                5,
                'yes',
                'Pcs',
                'ELK',
                'PPN',
                'SUP-002'
            ],
            [
                'Software Akuntansi Premium',
                'SA-001',
                'Software akuntansi untuk usaha kecil dan menengah',
                500000,
                750000,
                'service',
                1,
                'yes',
                'Pcs',
                'SW',
                '',
                'SUP-001'
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
            'min_order_qty',
            'is_active',
            'unit_code',
            'product_group_code',
            'tax_code',
            'supplier_code'
        ];
    }

    public function title(): string
    {
        return 'Template Impor Produk';
    }
}