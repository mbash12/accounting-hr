<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class BankReconciliationTemplateExport implements FromCollection, WithHeadings, WithColumnWidths
{
    public function collection()
    {
        return collect([
            [
                'date' => now()->format('Y-m-d'),
                'description' => 'Payment from PT ABC - INV/001',
                'debit' => 0,
                'credit' => 5000000,
            ],
            [
                'date' => now()->format('Y-m-d'),
                'description' => 'Bank admin fee June',
                'debit' => 25000,
                'credit' => 0,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Description',
            'Debit (Outgoing)',
            'Credit (Incoming)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 45,
            'C' => 20,
            'D' => 20,
        ];
    }
}
