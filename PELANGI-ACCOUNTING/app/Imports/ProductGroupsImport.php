<?php

namespace App\Imports;

use App\Models\ProductGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductGroupsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $productGroup = ProductGroup::where('code', (string) $row['product_group_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'code' => (string) $row['product_group_code'],
                'shipping_type' => (string) $row['shipping_type'],
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'ya' ||
                        strtolower((string) $row['active_status']) === 'yes' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($productGroup) {
                $productGroup->update($data);
            } else {
                $data['name'] = (string) $row['product_group_name'];
                $data['company_id'] = $companyId;
                ProductGroup::create($data);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        // Convert all fields to strings to satisfy validation rules
        return [
            'product_group_name' => isset($data['product_group_name']) ? (string) $data['product_group_name'] : null,
            'product_group_code' => isset($data['product_group_code']) ? (string) $data['product_group_code'] : null,
            'shipping_type' => isset($data['shipping_type']) ? (string) $data['shipping_type'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'product_group_name' => 'required|string|max:255',
            'product_group_code' => 'required|string|max:255',
            'shipping_type' => 'required|string|in:physical,digital',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'product_group_name.required' => 'Product Group Name is required.',
            'product_group_name.max' => 'Product Group Name cannot exceed 255 characters.',
            'product_group_code.required' => 'Product Group Code is required.',
            'product_group_code.max' => 'Product Group Code cannot exceed 255 characters.',
            'shipping_type.required' => 'Shipping Type is required.',
            'shipping_type.in' => 'Shipping Type must be "physical" or "digital".',
        ];
    }
}