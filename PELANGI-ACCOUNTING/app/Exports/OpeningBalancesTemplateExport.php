<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OpeningBalancesTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        if (!$companyId) {
            return collect([]);
        }

        $accounts = Account::where('company_id', $companyId)
            ->where('is_header', false)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $openingBalances = \App\Models\OpeningBalance::where('company_id', $companyId)
            ->get()
            ->keyBy('account_id');

        return $accounts->map(function ($account) use ($openingBalances) {
            $balance = $openingBalances->get($account->id);
            
            return [
                'account_code' => $account->code,
                'account_name' => $account->name,
                'debit_amount' => ($balance && $balance->balance_type === 'debit') ? (float) $balance->amount : 0,
                'credit_amount' => ($balance && $balance->balance_type === 'credit') ? (float) $balance->amount : 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'account_code',
            'account_name',
            'debit_amount',
            'credit_amount',
        ];
    }
}
