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
            'name.required' => 'Nama Aset Tetap wajib diisi.',
            'name.max' => 'Nama Aset Tetap tidak boleh lebih dari 200 karakter.',
            'code.required' => 'Kode Aset wajib diisi.',
            'code.max' => 'Kode Aset tidak boleh lebih dari 50 karakter.',
            'code.unique' => 'Kode Aset sudah digunakan.',
            'location.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',
            'acquisition_date.date' => 'Format tanggal perolehan tidak valid.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'acquisition_value.min' => 'Nilai perolehan tidak boleh kurang dari 0.',
            'acquisition_value.numeric' => 'Nilai perolehan harus berupa angka.',
            'monthly_depreciation.min' => 'Penyusutan bulanan tidak boleh kurang dari 0.',
            'monthly_depreciation.numeric' => 'Penyusutan bulanan harus berupa angka.',
            'depreciation_method.in' => 'Metode penyusutan harus salah satu dari: straight_line, double_declining, sum_of_years, declining_balance, units_of_production.',
            'accumulated_depreciation.min' => 'Akumulasi penyusutan tidak boleh kurang dari 0.',
            'accumulated_depreciation.numeric' => 'Akumulasi penyusutan harus berupa angka.',
            'useful_life.min' => 'Masa manfaat tidak boleh kurang dari 1 tahun.',
            'useful_life.max' => 'Masa manfaat tidak boleh lebih dari 100 tahun.',
            'useful_life.integer' => 'Masa manfaat harus berupa angka bulat.',
            'book_value.min' => 'Nilai buku tidak boleh kurang dari 0.',
            'book_value.numeric' => 'Nilai buku harus berupa angka.',
            'category_name.max' => 'Nama Kategori tidak boleh lebih dari 200 karakter.',
            'department_name.max' => 'Nama Departemen tidak boleh lebih dari 200 karakter.',
            'transaction_in_number.max' => 'Nomor Transaksi tidak boleh lebih dari 50 karakter.',
        ];
    }
}