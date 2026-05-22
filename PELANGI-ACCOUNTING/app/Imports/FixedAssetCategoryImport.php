<?php

namespace App\Imports;

use App\Models\FixedAssetCategory;
use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class FixedAssetCategoryImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find account IDs from account codes if provided
            $salesAccountId = null;
            $assetAccountId = null;
            $accumulatedDepreciationAccountId = null;
            $depreciationAccountId = null;

            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            if (!empty($row['sales_account_code'])) {
                $salesAccount = Account::where('code', (string) $row['sales_account_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $salesAccountId = $salesAccount ? $salesAccount->id : null;
            }

            if (!empty($row['asset_account_code'])) {
                $assetAccount = Account::where('code', (string) $row['asset_account_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $assetAccountId = $assetAccount ? $assetAccount->id : null;
            }

            if (!empty($row['accumulated_depreciation_account_code'])) {
                $accDepAccount = Account::where('code', (string) $row['accumulated_depreciation_account_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $accumulatedDepreciationAccountId = $accDepAccount ? $accDepAccount->id : null;
            }

            if (!empty($row['depreciation_account_code'])) {
                $depAccount = Account::where('code', (string) $row['depreciation_account_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $depreciationAccountId = $depAccount ? $depAccount->id : null;
            }

            // Verify accounts exist (though validation should catch this, scoping ensures we match correct company)
            // If validation passes, these lookups will return values.
            // If we want to be safe, we can default to null or throw exception, but 'continue' hides errors.

            // Convert all values to strings to avoid type errors

            $category = FixedAssetCategory::where('name', (string) $row['name'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'depreciation_method' => isset($row['depreciation_method']) ? (string) $row['depreciation_method'] : 'straight_line',
                'useful_life' => isset($row['useful_life']) ? (int) $row['useful_life'] : null,
                'is_active' => isset($row['is_active']) &&
                    (strtolower((string) $row['is_active']) === 'ya' ||
                        strtolower((string) $row['is_active']) === 'yes' ||
                        strtolower((string) $row['is_active']) === 'true' ||
                        (string) $row['is_active'] === '1'),
                'sales_account_id' => $salesAccountId,
                'asset_account_id' => $assetAccountId,
                'accumulated_depreciation_account_id' => $accumulatedDepreciationAccountId,
                'depreciation_account_id' => $depreciationAccountId,
                'created_by_user_id' => Auth::id(),
            ];

            if ($category) {
                $category->update($data);
            } else {
                $data['name'] = (string) $row['name'];
                $data['company_id'] = $companyId;
                // Only set code if provided in the import
                if (!empty($row['code'])) {
                    $data['code'] = (string) $row['code'];
                }
                FixedAssetCategory::create($data);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        // Convert is_active from text to boolean before validation
        $isActive = false;
        if (isset($data['is_active'])) {
            $isActive = \in_array(
                strtolower((string) $data['is_active']),
                ['ya', 'yes', 'true', '1', 'active']
            );
        }

        return [
            'name' => isset($data['name']) ? (string) $data['name'] : null,
            'code' => isset($data['code']) ? (string) $data['code'] : null,
            'depreciation_method' => isset($data['depreciation_method']) ? (string) $data['depreciation_method'] : null,
            'useful_life' => isset($data['useful_life']) ? (string) $data['useful_life'] : null,
            'is_active' => $isActive,
            'sales_account_code' => isset($data['sales_account_code']) ? (string) $data['sales_account_code'] : null,
            'sales_account_name' => isset($data['sales_account_name']) ? (string) $data['sales_account_name'] : null,
            'asset_account_code' => isset($data['asset_account_code']) ? (string) $data['asset_account_code'] : null,
            'asset_account_name' => isset($data['asset_account_name']) ? (string) $data['asset_account_name'] : null,
            'accumulated_depreciation_account_code' => isset($data['accumulated_depreciation_account_code']) ? (string) $data['accumulated_depreciation_account_code'] : null,
            'accumulated_depreciation_account_name' => isset($data['accumulated_depreciation_account_name']) ? (string) $data['accumulated_depreciation_account_name'] : null,
            'depreciation_account_code' => isset($data['depreciation_account_code']) ? (string) $data['depreciation_account_code'] : null,
            'depreciation_account_name' => isset($data['depreciation_account_name']) ? (string) $data['depreciation_account_name'] : null,
            'created_by_user_id' => Auth::check() ? Auth::id() : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'name' => 'required|string|max:200|unique:fixed_asset_categories,name,NULL,id,company_id,' . ($companyId ?? 'NULL') . ',deleted_at,NULL',
            'code' => 'nullable|string|max:50',
            'depreciation_method' => 'nullable|string|in:straight_line,double_declining,sum_of_years,declining_balance,units_of_production',
            'useful_life' => 'nullable|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
            'sales_account_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('accounts', 'code')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId)
                        ->orWhereNull('company_id');
                }),
            ],
            'sales_account_name' => 'nullable|string|max:200',
            'asset_account_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('accounts', 'code')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId)
                        ->orWhereNull('company_id');
                }),
            ],
            'asset_account_name' => 'nullable|string|max:200',
            'accumulated_depreciation_account_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('accounts', 'code')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId)
                        ->orWhereNull('company_id');
                }),
            ],
            'accumulated_depreciation_account_name' => 'nullable|string|max:200',
            'depreciation_account_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('accounts', 'code')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId)
                        ->orWhereNull('company_id');
                }),
            ],
            'depreciation_account_name' => 'nullable|string|max:200',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Fixed Asset Category Name is required.',
            'name.max' => 'Fixed Asset Category Name cannot exceed 200 characters.',
            'code.max' => 'Fixed Asset Category Code cannot exceed 50 characters.',
            'code.unique' => 'Fixed Asset Category Code is already in use.',
            'depreciation_method.in' => 'Depreciation method must be one of: straight_line, double_declining, sum_of_years, declining_balance, units_of_production.',
            'useful_life.min' => 'Useful life cannot be less than 1 year.',
            'useful_life.max' => 'Useful life cannot exceed 100 years.',
            'useful_life.integer' => 'Useful life must be a whole number.',
            'sales_account_code.max' => 'Sales Account Code cannot exceed 50 characters.',
            'sales_account_code.exists' => 'Sales Account Code not found in this company.',
            'asset_account_code.max' => 'Asset Account Code cannot exceed 50 characters.',
            'asset_account_code.exists' => 'Asset Account Code not found in this company.',
            'accumulated_depreciation_account_code.max' => 'Accumulated Depreciation Account Code cannot exceed 50 characters.',
            'accumulated_depreciation_account_code.exists' => 'Accumulated Depreciation Account Code not found in this company.',
            'depreciation_account_code.max' => 'Depreciation Account Code cannot exceed 50 characters.',
            'depreciation_account_code.exists' => 'Depreciation Account Code not found in this company.',
            'sales_account_name.max' => 'Sales Account Name cannot exceed 200 characters.',
            'asset_account_name.max' => 'Asset Account Name cannot exceed 200 characters.',
            'accumulated_depreciation_account_name.max' => 'Accumulated Depreciation Account Name cannot exceed 200 characters.',
            'depreciation_account_name.max' => 'Depreciation Account Name cannot exceed 200 characters.',
        ];
    }
}