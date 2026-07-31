<?php

namespace App\Exports;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct()
    {
        // Read the CSV file and parse it
        $csvPath = database_path('seeders/data/accounts.csv');
        $csvContent = File::get($csvPath);
        $lines = explode("\n", $csvContent);

        // Remove the header line and parse the data
        $header = str_getcsv(array_shift($lines), ',', '"', '\\');
        $this->data = [];

        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $row = str_getcsv($line, ',', '"', '\\');
                if (count($row) === count($header)) {
                    $this->data[] = array_combine($header, $row);
                }
            }
        }
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'code',
            'name',
            'description',
            'classification_type',
            'account_type',
            'is_header',
            'is_cash_bank',
            'is_active',
            'level',
            'parent_code',
        ];
    }

    public function map($row): array
    {
        return [
            $row['code'],
            $row['name'],
            $row['description'],
            $this->classificationTypeForImport($row['classification_type']),
            $this->accountTypeForImport($row['classification_type']),
            $row['is_header'],
            $row['is_cash_bank'],
            $row['is_active'],
            $row['level'],
            $row['parent_code'],
        ];
    }

    private function classificationTypeForImport(string $classificationType): string
    {
        return match ($classificationType) {
            'asset', 'current_asset', 'cash_bank', 'account_receivable', 'inventory',
            'fixed_asset', 'accumulated_depreciation', 'other_asset' => 'asset',
            'liability', 'current_liability', 'account_payable', 'long_term_liability' => 'liability',
            'equity' => 'equity',
            'revenue', 'other_revenue' => 'revenue',
            'expense', 'cogs', 'other_expense' => 'expense',
            default => 'asset',
        };
    }

    private function accountTypeForImport(string $classificationType): string
    {
        return match ($classificationType) {
            'asset', 'current_asset', 'cash_bank', 'account_receivable', 'inventory' => 'current_asset',
            'fixed_asset', 'accumulated_depreciation' => 'fixed_asset',
            'other_asset' => 'other_asset',
            'liability', 'current_liability', 'account_payable' => 'current_liability',
            'long_term_liability' => 'long_term_liability',
            'equity' => 'equity',
            'revenue' => 'revenue',
            'other_revenue' => 'other_income',
            'cogs' => 'cost_of_goods_sold',
            'other_expense' => 'other_expense',
            'expense' => 'expense',
            default => 'current_asset',
        };
    }
}
