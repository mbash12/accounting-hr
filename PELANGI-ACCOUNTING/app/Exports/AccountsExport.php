<?php

namespace App\Exports;

use App\Models\Account;
use App\Services\CompanyFilterService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $query = Account::select([
            'code', 'name', 'description', 'classification_type', 'is_header',
            'is_cash_bank', 'is_active', 'level', 'parent_id'
        ])->with('parent:id,code');

        // Only get accounts that belong to the selected company (exclude shared records)
        $selectedCompanyId = session('selected_company_id', 'all');

        if ($selectedCompanyId !== 'all' && $selectedCompanyId) {
            $query = $query->where('company_id', $selectedCompanyId);
        }

        return $query->orderBy('code')->get();
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

    public function map($account): array
    {
        // Get parent code if exists
        return [
            $account->code,
            $account->name,
            $account->description,
            $account->classification_type,
            $account->is_header ? 'yes' : 'no',
            $account->is_cash_bank ? 'yes' : 'no',
            $account->is_active ? 'yes' : 'no',
            $account->level,
            $account->parent?->code,
        ];
    }
}
