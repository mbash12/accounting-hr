<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Unit;
use App\Models\ProductGroup;
use App\Models\Tax;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors, then convert back as needed
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            // Find unit from unit code
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', 'ilike', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company");
                }
                $unitId = $unit->id;
            } else {
                throw new \Exception("Unit code is required");
            }

            // Find product group from group code
            if (!empty($row['product_group_code'])) {
                $productGroup = ProductGroup::where('code', (string) $row['product_group_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$productGroup) {
                    throw new \Exception("Product group with code '{$row['product_group_code']}' not found in current company");
                }
                $productGroupId = $productGroup->id;
            } else {
                $productGroupId = null; // Product group is optional
            }

            // Find tax from tax code if tax_code is provided
            $taxId = null;
            if (!empty($row['tax_code'])) {
                $tax = Tax::where('code', (string) $row['tax_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$tax) {
                    throw new \Exception("Tax with code '{$row['tax_code']}' not found in current company");
                }
                $taxId = $tax->id;
            }

            $product = Product::where('code', (string) $row['code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => isset($row['name']) ? (string) $row['name'] : null,
                'description' => isset($row['description']) ? (string) $row['description'] : null,
                'cost_price' => isset($row['cost_price']) ? (float) $row['cost_price'] : 0,
                'selling_price' => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
                'reorder_level' => isset($row['reorder_level']) ? (float) $row['reorder_level'] : 0,
                'max_stock' => isset($row['max_stock']) ? (float) $row['max_stock'] : 0,
                'weight' => isset($row['weight']) ? (float) $row['weight'] : 0,
                'product_type' => isset($row['product_type']) && in_array($row['product_type'], ['good', 'service']) ? (string) $row['product_type'] : 'good',
                'is_active' => isset($row['is_active']) &&
                    (strtolower((string) $row['is_active']) === 'yes' ||
                        strtolower((string) $row['is_active']) === 'true' ||
                        (string) $row['is_active'] === '1') ?: true,
                'unit_id' => $unitId,
                'product_group_id' => $productGroupId,
                'tax_id' => $taxId,
                'created_by_user_id' => Auth::id(),
            ];

            if ($product) {
                $product->update($data);
            } else {
                $data['code'] = (string) $row['code'];
                $data['company_id'] = $companyId;
                Product::create($data);
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
                ['yes', 'true', '1', 'active']
            );
        }

        return [
            'name' => isset($data['name']) ? (string) $data['name'] : null,
            'code' => isset($data['code']) ? (string) $data['code'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'cost_price' => isset($data['cost_price']) ? (string) $data['cost_price'] : null,
            'selling_price' => isset($data['selling_price']) ? (string) $data['selling_price'] : null,
            'reorder_level' => isset($data['reorder_level']) ? (string) $data['reorder_level'] : null,
            'max_stock' => isset($data['max_stock']) ? (string) $data['max_stock'] : null,
            'weight' => isset($data['weight']) ? (string) $data['weight'] : null,
            'product_type' => isset($data['product_type']) ? (string) $data['product_type'] : null,
            'is_active' => $isActive,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
            'product_group_code' => isset($data['product_group_code']) ? (string) $data['product_group_code'] : null,
            'tax_code' => isset($data['tax_code']) ? (string) $data['tax_code'] : null,
            'created_by_user_id' => Auth::check() ? Auth::id() : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'product_type' => 'nullable|in:good,service',
            'is_active' => 'nullable|boolean',
            'unit_code' => 'required|string|max:20',
            'product_group_code' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:50',
            'created_by_user_id' => 'nullable|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama Produk wajib diisi.',
            'name.max' => 'Nama Produk tidak boleh lebih dari 255 karakter.',
            'code.required' => 'Kode Produk wajib diisi.',
            'code.max' => 'Kode Produk tidak boleh lebih dari 50 karakter.',
            'code.unique' => 'Kode Produk sudah digunakan.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'cost_price.min' => 'Harga beli tidak boleh kurang dari 0.',
            'cost_price.numeric' => 'Harga beli harus berupa angka.',
            'selling_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'selling_price.numeric' => 'Harga jual harus berupa angka.',
            'reorder_level.min' => 'Stok minimum tidak boleh kurang dari 0.',
            'reorder_level.numeric' => 'Stok minimum harus berupa angka.',
            'max_stock.min' => 'Stok maksimum tidak boleh kurang dari 0.',
            'max_stock.numeric' => 'Stok maksimum harus berupa angka.',
            'weight.min' => 'Berat tidak boleh kurang dari 0.',
            'weight.numeric' => 'Berat harus berupa angka.',
            'product_type.max' => 'Tipe Produk tidak boleh lebih dari 50 karakter.',
            'unit_code.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
            'product_group_code.max' => 'Kode Grup Produk tidak boleh lebih dari 255 karakter.',
            'tax_code.max' => 'Kode Pajak tidak boleh lebih dari 50 karakter.',
        ];
    }
}