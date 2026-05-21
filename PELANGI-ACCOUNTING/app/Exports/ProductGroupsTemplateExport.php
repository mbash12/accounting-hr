<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductGroupsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Electronics',
                'ELK',
                'physical',
                'yes'
            ],
            [
                'Software',
                'SW',
                'digital',
                'yes'
            ],
            [
                'Books',
                'BKS',
                'physical',
                'yes'
            ],
            [
                'Online Courses',
                'KUR',
                'digital',
                'yes'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'product_group_name',
            'product_group_code',
            'shipping_type',
            'active_status'
        ];
    }

    public function title(): string
    {
        return 'Product Group Import Template';
    }
}
