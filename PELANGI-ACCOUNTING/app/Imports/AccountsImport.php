<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccountsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        if (!$companyId) {
            throw new \Exception('Company ID not found. Please select a company first.');
        }

        DB::beginTransaction();

        try {
            $codeToIdMap = [];

            // First pass: Create all accounts without parent relationships
            foreach ($rows as $row) {
                // Convert all values to strings to avoid type errors
                $code = (string) $row['code'];
                $name = (string) $row['name'];
                $description = $row['description'] ? (string) $row['description'] : '';
                $classificationType = $row['classification_type'] ? (string) $row['classification_type'] : 'asset';
                $isHeader = isset($row['is_header']) &&
                    (strtolower((string) $row['is_header']) === 'yes' ||
                        strtolower((string) $row['is_header']) === 'true' ||
                        (string) $row['is_header'] === '1' ||
                        (string) $row['is_header'] === 'true'); // Handle boolean 'true' from CSV
                $isCashBank = isset($row['is_cash_bank']) &&
                    (strtolower((string) $row['is_cash_bank']) === 'yes' ||
                        strtolower((string) $row['is_cash_bank']) === 'true' ||
                        (string) $row['is_cash_bank'] === '1' ||
                        (string) $row['is_cash_bank'] === 'true'); // Handle boolean 'true' from CSV
                $isActive = isset($row['is_active']) &&
                    (strtolower((string) $row['is_active']) === 'yes' ||
                        strtolower((string) $row['is_active']) === 'true' ||
                        (string) $row['is_active'] === '1' ||
                        (string) $row['is_active'] === 'true'); // Handle boolean 'true' from CSV
                $level = (int) ($row['level'] ?? 1);
                $parentCode = $row['parent_code'] ? (string) $row['parent_code'] : null;

                // Check if account already exists within the same company
                $account = Account::where('code', $code)
                    ->where('company_id', $companyId)
                    ->first();

                // Map classification_type to valid account_type values if account_type is not provided
                $accountType = $row['account_type'] ?? $classificationType;
                if (!$row['account_type']) {
                    // Map general classification types to specific account types based on ManageAccounts form options
                    switch ($classificationType) {
                        case 'asset':
                            $accountType = 'current_asset'; // Default to current_asset for asset
                            break;
                        case 'liability':
                            $accountType = 'current_liability'; // Default to current_liability for liability
                            break;
                        case 'equity':
                            $accountType = 'equity';
                            break;
                        case 'revenue':
                            $accountType = 'revenue';
                            break;
                        case 'expense':
                            $accountType = 'expense';
                            break;
                        // If it's already a specific type, use it as is
                        case 'current_asset':
                        case 'fixed_asset':
                        case 'other_asset':
                        case 'current_liability':
                        case 'long_term_liability':
                        case 'cost_of_goods_sold':
                        case 'other_income':
                        case 'other_expense':
                            $accountType = $classificationType;
                            break;
                        default:
                            $accountType = 'current_asset'; // Default fallback
                    }
                }

                // Determine the main classification type for the database constraint
                // The database constraint likely only allows main categories: asset, liability, equity, revenue, expense
                $mainClassificationType = $classificationType;

                // Map detailed classification types to main categories for the classification_type field
                if (in_array($classificationType, ['current_asset', 'fixed_asset', 'other_asset'])) {
                    $mainClassificationType = 'asset';
                } elseif (in_array($classificationType, ['current_liability', 'long_term_liability'])) {
                    $mainClassificationType = 'liability';
                } elseif (in_array($classificationType, ['cost_of_goods_sold', 'other_expense'])) {
                    $mainClassificationType = 'expense';
                } elseif ($classificationType === 'other_income') {
                    $mainClassificationType = 'revenue';
                }
                // 'asset', 'liability', 'equity', 'revenue', 'expense' remain as they are

                $data = [
                    'name' => $name,
                    'description' => $description,
                    'classification_type' => $mainClassificationType,
                    'account_type' => $accountType,
                    'is_header' => $isHeader,
                    'is_cash_bank' => $isCashBank,
                    'is_active' => $isActive,
                    'cash_flow' => $row['cash_flow'] ?? 'undefined',
                    'level' => $level,
                    'opening_balance' => 0,
                    'current_balance' => 0,
                    'company_id' => $companyId,
                    'created_by_user_id' => Auth::check() ? Auth::id() : session('current_user_id'),
                ];

                if ($account) {
                    // Update existing account
                    $account->update($data);
                    $codeToIdMap[$code] = $account->id;
                } else {
                    // Create new account
                    $newAccount = Account::create(array_merge($data, ['code' => $code, 'parent_id' => null]));
                    $codeToIdMap[$code] = $newAccount->id;
                }
            }

            // Second pass: Update parent relationships
            foreach ($rows as $row) {
                $code = (string) $row['code'];
                $parentCode = $row['parent_code'] ? (string) $row['parent_code'] : null;

                if ($parentCode && isset($codeToIdMap[$parentCode])) {
                    $accountId = $codeToIdMap[$code];
                    // Update parent relationship within the same company
                    Account::where('id', $accountId)
                        ->where('company_id', $companyId)  // Ensure we only update accounts from the same company
                        ->update(['parent_id' => $codeToIdMap[$parentCode]]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        // Convert all fields to strings to satisfy validation rules
        return [
            'code' => isset($data['code']) ? (string) $data['code'] : null,
            'name' => isset($data['name']) ? (string) $data['name'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'classification_type' => isset($data['classification_type']) ? (string) $data['classification_type'] : null,
            'is_header' => isset($data['is_header']) ? (string) $data['is_header'] : null,
            'is_cash_bank' => isset($data['is_cash_bank']) ? (string) $data['is_cash_bank'] : null,
            'is_active' => isset($data['is_active']) ? (string) $data['is_active'] : null,
            'level' => isset($data['level']) ? (string) $data['level'] : null,
            'parent_code' => isset($data['parent_code']) ? (string) $data['parent_code'] : null,
            'account_type' => isset($data['account_type']) ? (string) $data['account_type'] : null,
            'cash_flow' => isset($data['cash_flow']) ? (string) $data['cash_flow'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'classification_type' => 'required|string|in:asset,liability,equity,revenue,expense,current_asset,fixed_asset,other_asset,current_liability,long_term_liability,cost_of_goods_sold,other_income,other_expense',
            'is_header' => 'nullable|string',
            'is_cash_bank' => 'nullable|string',
            'is_active' => 'nullable|string',
            'level' => 'required|integer|min:1',
            'parent_code' => 'nullable|string|max:50',
            'account_type' => 'nullable|string|in:asset,liability,equity,revenue,expense,current_asset,fixed_asset,other_asset,current_liability,long_term_liability,cost_of_goods_sold,other_income,other_expense',
            'cash_flow' => 'nullable|string|in:operating,investing,financing,undefined',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'code.required' => 'Account Code is required.',
            'code.max' => 'Account Code cannot exceed 50 characters.',
            'name.required' => 'Account Name is required.',
            'name.max' => 'Account Name cannot exceed 200 characters.',
            'classification_type.required' => 'Classification Type is required.',
            'classification_type.in' => 'Classification Type must be one of: asset, liability, equity, revenue, expense.',
            'level.required' => 'Level is required.',
            'level.integer' => 'Level must be a number.',
            'level.min' => 'Level must be at least 1.',
        ];
    }
}