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
                    'Kode Termin' => $paymentTerm->code,
                    'Nama Termin' => $paymentTerm->name,
                    'Jumlah Hari' => $paymentTerm->due_days,
                    'Status Aktif' => $paymentTerm->is_active ? 'ya' : 'tidak',
                    'Deskripsi' => $paymentTerm->description,
                    'Dibuat Pada' => $paymentTerm->created_at ? $paymentTerm->created_at->format('Y-m-d H:i:s') : null,
                    'Diubah Pada' => $paymentTerm->updated_at ? $paymentTerm->updated_at->format('Y-m-d H:i:s') : null,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Termin',
            'Nama Termin',
            'Jumlah Hari',
            'Status Aktif',
            'Deskripsi',
            'Dibuat Pada',
            'Diubah Pada',
        ];
    }

    public function title(): string
    {
        return 'Data Termin Pembayaran';
    }
}
