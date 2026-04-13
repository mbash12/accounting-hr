<?php

namespace App\Imports;

use App\Models\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnitMeasurementsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $unit = Unit::where('code', (string) $row['unit_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => (string) $row['unit_name'],
                'description' => isset($row['unit_description']) ? (string) $row['unit_description'] : null,
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'ya' ||
                        strtolower((string) $row['active_status']) === 'yes' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($unit) {
                $unit->update($data);
            } else {
                $data['code'] = (string) $row['unit_code'];
                $data['company_id'] = $companyId;
                Unit::create($data);
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
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
            'unit_name' => isset($data['unit_name']) ? (string) $data['unit_name'] : null,
            'unit_description' => isset($data['unit_description']) ? (string) $data['unit_description'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'unit_code' => 'required|string|max:20',
            'unit_name' => 'required|string|max:255',
            'unit_description' => 'nullable|string|max:500',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unit_code.required' => 'Kode Satuan wajib diisi.',
            'unit_code.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
            'unit_code.unique' => 'Kode Satuan sudah digunakan.',
            'unit_name.required' => 'Nama Satuan wajib diisi.',
            'unit_name.max' => 'Nama Satuan tidak boleh lebih dari 255 karakter.',
            'unit_description.max' => 'Deskripsi Satuan tidak boleh lebih dari 500 karakter.',
        ];
    }
}