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
            $row['classification_type'],
            $row['is_header'],
            $row['is_cash_bank'],
            $row['is_active'],
            $row['level'],
            $row['parent_code'],
        ];
    }
}