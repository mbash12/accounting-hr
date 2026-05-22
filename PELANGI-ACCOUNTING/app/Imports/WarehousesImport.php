<?php

namespace App\Imports;

use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehousesImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $warehouse = Warehouse::where('code', (string) $row['warehouse_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => (string) $row['warehouse_name'],
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'ya' ||
                        strtolower((string) $row['active_status']) === 'yes' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($warehouse) {
                $warehouse->update($data);
            } else {
                $data['code'] = (string) $row['warehouse_code'];
                $data['company_id'] = $companyId;
                Warehouse::create($data);
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
            'warehouse_code' => isset($data['warehouse_code']) ? (string) $data['warehouse_code'] : null,
            'warehouse_name' => isset($data['warehouse_name']) ? (string) $data['warehouse_name'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'warehouse_code' => 'required|string|max:20',
            'warehouse_name' => 'required|string|max:255',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'warehouse_code.required' => 'Warehouse Code is required.',
            'warehouse_code.max' => 'Warehouse Code cannot exceed 20 characters.',
            'warehouse_name.required' => 'Warehouse Name is required.',
            'warehouse_name.max' => 'Warehouse Name cannot exceed 255 characters.',
        ];
    }
}