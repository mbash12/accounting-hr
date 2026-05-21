<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehousesTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'WH001',
                'Main Warehouse',
                'yes'
            ],
            [
                'WH002',
                'Raw Materials Warehouse',
                'yes'
            ],
            [
                'WH003',
                'Finished Goods Warehouse',
                'yes'
            ],
            [
                'WH004',
                'Temporary Warehouse',
                'no'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'warehouse_code',
            'warehouse_name',
            'active_status'
        ];
    }

    public function title(): string
    {
        return 'Warehouse Import Template';
    }
}
