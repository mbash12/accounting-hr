<?php

namespace App\Exports;

use App\Models\PaymentTerm;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentTermExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = PaymentTerm::select([
            'code',
            'name',
            'due_days',
            'is_active',
            'description',
            'created_at',
            'updated_at',
        ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($paymentTerm) {
                return [
                    'Term Code' => $paymentTerm->code,
                    'Term Name' => $paymentTerm->name,
                    'Due Days' => $paymentTerm->due_days,
                    'Active Status' => $paymentTerm->is_active ? 'Yes' : 'No',
                    'Description' => $paymentTerm->description,
                    'Created At' => $paymentTerm->created_at ? $paymentTerm->created_at->format('Y-m-d H:i:s') : null,
                    'Updated At' => $paymentTerm->updated_at ? $paymentTerm->updated_at->format('Y-m-d H:i:s') : null,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Term Code',
            'Term Name',
            'Due Days',
            'Active Status',
            'Description',
            'Created At',
            'Updated At',
        ];
    }

    public function title(): string
    {
        return 'Payment Term Data';
    }
}
