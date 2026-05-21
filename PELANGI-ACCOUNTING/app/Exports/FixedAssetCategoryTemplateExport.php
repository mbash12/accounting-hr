<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FixedAssetCategoryTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Operational Vehicles',
                'VEHICLES',
                'straight_line',
                '5',
                'yes',
                '40000100',
                'Sales',
                '17000300',
                'Vehicles',
                '17100200',
                'Accumulated Depreciation - Vehicles',
                '63000200',
                'Depreciation Expense - Vehicles',
            ],
            [
                'Office Equipment',
                'OFFICE',
                'double_declining',
                '4',
                'yes',
                '40000100',
                'Sales',
                '17000400',
                'Office Equipment Group 1',
                '17100300',
                'Accumulated Depreciation - Office Equipment Group I',
                '63000300',
                'Depreciation Expense - Equipment',
            ],
            [
                'Buildings',
                'BUILDING',
                'straight_line',
                '20',
                'yes',
                '40000100',
                'Sales',
                '17000200',
                'Buildings',
                '17100100',
                'Accumulated Depreciation - Buildings',
                '63000100',
                'Depreciation Expense - Buildings',
            ],
            [
                'Production Machinery',
                'MACHINERY',
                'sum_of_years',
                '10',
                'no',
                '40000100',
                'Sales',
                '17000500',
                'Office Equipment Group 2',
                '17100400',
                'Accumulated Depreciation - Office Equipment Group II',
                '63000300',
                'Depreciation Expense - Equipment',
            ],
            [
                'Computers & IT',
                'COMPUTER',
                'straight_line',
                '4',
                'yes',
                '40000100',
                'Sales',
                '17000400',
                'Office Equipment Group 1',
                '17100300',
                'Accumulated Depreciation - Office Equipment Group I',
                '63000300',
                'Depreciation Expense - Equipment',
            ],
            [
                'Other Equipment',
                'EQUIPMENT',
                'straight_line',
                '5',
                'yes',
                '40000100',
                'Sales',
                '17000400',
                'Office Equipment Group 1',
                '17100300',
                'Accumulated Depreciation - Office Equipment Group I',
                '63000300',
                'Depreciation Expense - Equipment',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'depreciation_method',
            'useful_life',
            'is_active',
            'sales_account_code',
            'sales_account_name',
            'asset_account_code',
            'asset_account_name',
            'accumulated_depreciation_account_code',
            'accumulated_depreciation_account_name',
            'depreciation_account_code',
            'depreciation_account_name'
        ];
    }

    public function title(): string
    {
        return 'Fixed Asset Category Import Template';
    }
}
