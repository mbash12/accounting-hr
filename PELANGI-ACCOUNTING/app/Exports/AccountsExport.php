<?php

namespace App\Exports;

use App\Models\Account;
use App\Services\CompanyFilterService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    private const IMPORT_CLASSIFICATION_TYPES = [
        'asset',
        'liability',
        'equity',
        'revenue',
        'expense',
        'current_asset',
        'fixed_asset',
        'other_asset',
        'current_liability',
        'long_term_liability',
        'cost_of_goods_sold',
        'other_income',
        'other_expense',
        'other_income_expense',
    ];

    public function collection()
    {
        $query = Account::select([
            'code', 'name', 'description', 'classification_type', 'account_type', 'is_header',
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
            $this->classificationTypeForImport($account),
            $account->is_header ? 'yes' : 'no',
            $account->is_cash_bank ? 'yes' : 'no',
            $account->is_active ? 'yes' : 'no',
            $account->level,
            $account->parent?->code,
        ];
    }

    private function classificationTypeForImport(Account $account): string
    {
        $legacyClassification = $this->classificationTypeFromStandardCode($account);

        if ($legacyClassification !== null) {
            return $legacyClassification;
        }

        if (
            $account->parent_id === null
            && in_array($account->classification_type, [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense',
            ], true)
        ) {
            return $account->classification_type;
        }

        if (in_array($account->account_type, self::IMPORT_CLASSIFICATION_TYPES, true)) {
            return match ($account->account_type) {
                'cost_of_goods_sold', 'other_income_expense' => 'expense',
                'current_liability' => 'liability',
                default => $account->account_type,
            };
        }

        if (in_array($account->classification_type, self::IMPORT_CLASSIFICATION_TYPES, true)) {
            return $account->classification_type;
        }

        return 'asset';
    }

    private function classificationTypeFromStandardCode(Account $account): ?string
    {
        $code = trim((string) $account->code);

        if (
            $account->classification_type === 'asset'
            && $account->account_type === 'current_asset'
        ) {
            return match (true) {
                $code === '12' => 'current_asset',
                $code === '12.02' || str_starts_with($code, '12.02.') => 'fixed_asset',
                $code === '14' || str_starts_with($code, '14.') => 'fixed_asset',
                default => null,
            };
        }

        if (
            $account->classification_type === 'expense'
            && $account->account_type === 'expense'
        ) {
            return match (true) {
                $code === '60' || $code === '60.01' || str_starts_with($code, '60.01.') => 'other_income',
                $code === '60.02' || str_starts_with($code, '60.02.') => 'other_expense',
                default => null,
            };
        }

        return null;
    }
}
