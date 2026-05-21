<?php

namespace App\Exports;

use App\Models\Bank;
use App\Services\CompanyFilterService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BanksExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $query = Bank::select([
            'code', 'name', 'logo', 'country', 'clearing_code', 'skn_code',
            'is_active'
        ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Bank Code',
            'Bank Name',
            'Country',
            'Clearing Code',
            'SKN Code',
            'Active Status',
        ];
    }

    public function map($bank): array
    {
        return [
            $bank->code,
            $bank->name,
            $bank->country,
            $bank->clearing_code,
            $bank->skn_code,
            $bank->is_active ? 'Yes' : 'No',
        ];
    }
}
