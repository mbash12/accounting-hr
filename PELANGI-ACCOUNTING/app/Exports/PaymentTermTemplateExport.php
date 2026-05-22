<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentTermTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        // Return sample data as template
        return collect([
            [
                'CASH',
                'Cash',
                0,
                'Yes',
                'Cash payment at time of transaction',
            ],
            [
                'NET15',
                'Net 15',
                15,
                'Yes',
                'Payment due in 15 days',
            ],
            [
                'NET30',
                'Net 30',
                30,
                'Yes',
                'Payment due in 30 days',
            ],
            [
                'NET45',
                'Net 45',
                45,
                'Yes',
                'Payment due in 45 days',
            ],
            [
                'NET60',
                'Net 60',
                60,
                'No',
                'Payment due in 60 days',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'term_code',
            'term_name',
            'due_days',
            'active_status',
            'description',
        ];
    }

    public function title(): string
    {
        return 'Payment Term Import Template';
    }
}
