<?php

namespace App\Imports;

use App\Models\FixedAsset;
use App\Models\FixedAssetCategory;
use App\Models\Department;
use App\Models\JournalEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FixedAssetImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        // Convert all values to strings to avoid type errors, then convert back as needed
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        foreach ($rows as $row) {
            // Find category ID from category code if provided
            $categoryId = null;
            if (!empty($row['category_code'])) {
                $category = FixedAssetCategory::where('code', (string) $row['category_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $categoryId = $category ? $category->id : null;
            }

            // Find department ID from department name if provided
            $departmentId = null;
            if (!empty($row['department_name'])) {
                $department = Department::where('name', (string) $row['department_name'])
                    ->where('company_id', $companyId)
                    ->first();
                $departmentId = $department ? $department->id : null;
            }

            // Find transaction ID from transaction number if provided
            $transactionInId = null;
            if (!empty($row['transaction_in_number'])) {
                $transaction = JournalEntry::where('entry_number', (string) $row['transaction_in_number'])
                    ->where('company_id', $companyId)
                    ->first();
                $transactionInId = $transaction ? $transaction->id : null;
            }

            $fixedAsset = FixedAsset::where('code', (string) $row['code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => isset($row['name']) ? (string) $row['name'] : null,
                'location' => isset($row['location']) ? (string) $row['location'] : null,
                'acquisition_date' => isset($row['acquisition_date']) ? (string) $row['acquisition_date'] : null,
                'description' => isset($row['description']) ? (string) $row['description'] : null,
                'acquisition_value' => isset($row['acquisition_value']) ? (float) $row['acquisition_value'] : null,
                'monthly_depreciation' => isset($row['monthly_depreciation']) ? (float) $row['monthly_depreciation'] : null,
                'depreciation_method' => isset($row['depreciation_method']) ? (string) $row['depreciation_method'] : 'straight_line',
                'accumulated_depreciation' => isset($row['accumulated_depreciation']) ? (float) $row['accumulated_depreciation'] : null,
                'useful_life' => isset($row['useful_life']) ? (int) $row['useful_life'] : null,
                'book_value' => isset($row['book_value']) ? (float) $row['book_value'] : null,
                'is_active' => isset($row['is_active']) &&
                    (strtolower((string) $row['is_active']) === 'ya' ||
                        strtolower((string) $row['is_active']) === 'yes' ||
                        strtolower((string) $row['is_active']) === 'true' ||
                        (string) $row['is_active'] === '1'),
                'category_id' => $categoryId,
                'department_id' => $departmentId,
                'transaction_in_id' => $transactionInId,
                'created_by_user_id' => Auth::id(),
            ];

            if ($fixedAsset) {
                $fixedAsset->update($data);
            } else {
                $data['code'] = (string) $row['code'];
                $data['company_id'] = $companyId;
                FixedAsset::create($data);
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
            'location' => isset($data['location']) ? (string) $data['location'] : null,
            'acquisition_date' => isset($data['acquisition_date']) ? (string) $data['acquisition_date'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'acquisition_value' => isset($data['acquisition_value']) ? (string) $data['acquisition_value'] : null,
            'monthly_depreciation' => isset($data['monthly_depreciation']) ? (string) $data['monthly_depreciation'] : null,
            'depreciation_method' => isset($data['depreciation_method']) ? (string) $data['depreciation_method'] : null,
            'accumulated_depreciation' => isset($data['accumulated_depreciation']) ? (string) $data['accumulated_depreciation'] : null,
            'useful_life' => isset($data['useful_life']) ? (string) $data['useful_life'] : null,
            'book_value' => isset($data['book_value']) ? (string) $data['book_value'] : null,
            'is_active' => $isActive,
            'category_code' => isset($data['category_code']) ? (string) $data['category_code'] : null,
            'category_id' => isset($data['category_id']) ? (string) $data['category_id'] : null,
            'department_name' => isset($data['department_name']) ? (string) $data['department_name'] : null,
            'department_id' => isset($data['department_id']) ? (string) $data['department_id'] : null,
            'transaction_in_number' => isset($data['transaction_in_number']) ? (string) $data['transaction_in_number'] : null,
            'transaction_in_id' => isset($data['transaction_in_id']) ? (string) $data['transaction_in_id'] : null,
            'created_by_user_id' => Auth::check() ? Auth::id() : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
            'acquisition_value' => 'nullable|numeric|min:0',
            'monthly_depreciation' => 'nullable|numeric|min:0',
            'depreciation_method' => 'nullable|string|in:straight_line,double_declining,sum_of_years,declining_balance,units_of_production',
            'accumulated_depreciation' => 'nullable|numeric|min:0',
            'useful_life' => 'nullable|integer|min:1|max:100',
            'book_value' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'category_code' => 'nullable|string|max:50',
            'department_name' => 'nullable|string|max:200',
            'transaction_in_number' => 'nullable|string|max:50',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Fixed Asset Name is required.',
            'name.max' => 'Fixed Asset Name cannot exceed 200 characters.',
            'code.required' => 'Asset Code is required.',
            'code.max' => 'Asset Code cannot exceed 50 characters.',
            'code.unique' => 'Asset Code is already in use.',
            'location.max' => 'Location cannot exceed 255 characters.',
            'acquisition_date.date' => 'Acquisition date format is not valid.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'acquisition_value.min' => 'Acquisition Value cannot be less than 0.',
            'acquisition_value.numeric' => 'Acquisition Value must be a number.',
            'monthly_depreciation.min' => 'Monthly Depreciation cannot be less than 0.',
            'monthly_depreciation.numeric' => 'Monthly Depreciation must be a number.',
            'depreciation_method.in' => 'Depreciation method must be one of: straight_line, double_declining, sum_of_years, declining_balance, units_of_production.',
            'accumulated_depreciation.min' => 'Accumulated Depreciation cannot be less than 0.',
            'accumulated_depreciation.numeric' => 'Accumulated Depreciation must be a number.',
            'useful_life.min' => 'Useful Life cannot be less than 1 year.',
            'useful_life.max' => 'Useful Life cannot exceed 100 years.',
            'useful_life.integer' => 'Useful Life must be a whole number.',
            'book_value.min' => 'Book Value cannot be less than 0.',
            'book_value.numeric' => 'Book Value must be a number.',
            'category_name.max' => 'Category Name cannot exceed 200 characters.',
            'department_name.max' => 'Department Name cannot exceed 200 characters.',
            'transaction_in_number.max' => 'Transaction Number cannot exceed 50 characters.',
        ];
    }
}