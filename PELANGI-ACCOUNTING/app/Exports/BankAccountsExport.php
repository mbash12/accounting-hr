<?php

namespace App\Exports;

use App\Models\BankAccount;
use App\Services\CompanyFilterService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BankAccountsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $query = BankAccount::with('bank')->select([
            'bank_accounts.bank_id',
            'bank_accounts.account_number',
            'bank_accounts.account_name',
            'bank_accounts.is_active',
        ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'bank_code',
            'account_number',
            'account_name',
            'active_status',
        ];
    }

    public function map($account): array
    {
        return [
            $account->bank?->code ?? '',
            $account->account_number,
            $account->account_name,
            $account->is_active ? 'Yes' : 'No',
        ];
    }
}
