<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $department = Department::where('code', (string) $row['department_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => (string) $row['department_name'],
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'yes' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($department) {
                $department->update($data);
            } else {
                $data['code'] = (string) $row['department_code'];
                $data['company_id'] = $companyId;
                Department::create($data);
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
            'department_code' => isset($data['department_code']) ? (string) $data['department_code'] : null,
            'department_name' => isset($data['department_name']) ? (string) $data['department_name'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'department_code' => 'required|string|max:20',
            'department_name' => 'required|string|max:255',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'department_code.required' => 'Kode Departemen wajib diisi.',
            'department_code.max' => 'Kode Departemen tidak boleh lebih dari 20 karakter.',
            'department_name.required' => 'Nama Departemen wajib diisi.',
            'department_name.max' => 'Nama Departemen tidak boleh lebih dari 255 karakter.',
        ];
    }
}