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

            $warehouse = Warehouse::where('code', (string) $row['kode_gudang'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => (string) $row['nama_gudang'],
                'is_active' => isset($row['status_aktif']) &&
                    (strtolower((string) $row['status_aktif']) === 'ya' ||
                        strtolower((string) $row['status_aktif']) === 'yes' ||
                        strtolower((string) $row['status_aktif']) === 'true' ||
                        (string) $row['status_aktif'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($warehouse) {
                $warehouse->update($data);
            } else {
                $data['code'] = (string) $row['kode_gudang'];
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
            'kode_gudang' => isset($data['kode_gudang']) ? (string) $data['kode_gudang'] : null,
            'nama_gudang' => isset($data['nama_gudang']) ? (string) $data['nama_gudang'] : null,
            'status_aktif' => isset($data['status_aktif']) ? (string) $data['status_aktif'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'kode_gudang' => 'required|string|max:20',
            'nama_gudang' => 'required|string|max:255',
            'status_aktif' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_gudang.required' => 'Warehouse Code is required.',
            'kode_gudang.max' => 'Warehouse Code cannot exceed 20 characters.',
            'nama_gudang.required' => 'Warehouse Name is required.',
            'nama_gudang.max' => 'Warehouse Name cannot exceed 255 characters.',
        ];
    }
}