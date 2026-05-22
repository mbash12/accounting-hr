<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PermitsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['EMP-001', 'sick', '2025-04-05', '2025-04-06', 'Fever', 'approved'],
            ['EMP-002', 'annual', '2025-04-10', '2025-04-12', 'Family vacation', 'pending'],
            ['EMP-003', 'others', '2025-04-03', '2025-04-03', 'Personal matter', 'approved'],
        ];
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'type',
            'start_date',
            'end_date',
            'reason',
            'status',
        ];
    }

    public function title(): string
    {
        return 'Permits & Leave Import Template';
    }
}
