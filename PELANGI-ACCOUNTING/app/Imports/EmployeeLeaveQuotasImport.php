<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeLeaveQuotasImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
            ? session('selected_company_id')
            : null;

        foreach ($rows as $row) {
            $employee = Employee::where('employee_id', (string) $row['employee_id'])
                ->where('company_id', $companyId)
                ->first();

            if (!$employee) {
                continue;
            }

            $year = (int) $row['year'];

            $data = [
                'employee_id'        => $employee->id,
                'year'               => $year,
                'total_quota'        => isset($row['total_quota']) ? (int) $row['total_quota'] : 12,
                'used_quota'         => isset($row['used_quota']) ? (int) $row['used_quota'] : 0,
                'created_by_user_id' => Auth::id(),
            ];

            $existing = EmployeeLeaveQuota::where('employee_id', $employee->id)
                ->where('year', $year)
                ->where('company_id', $companyId)
                ->first();

            if ($existing) {
                $existing->update($data);
            } else {
                $data['company_id'] = $companyId;
                EmployeeLeaveQuota::create($data);
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'employee_id' => isset($data['employee_id']) ? (string) $data['employee_id'] : null,
            'year'        => isset($data['year']) ? $data['year'] : null,
            'total_quota' => isset($data['total_quota']) ? $data['total_quota'] : null,
            'used_quota'  => isset($data['used_quota']) ? $data['used_quota'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string',
            'year'        => 'required|integer|min:2000|max:2100',
            'total_quota' => 'nullable|integer|min:0',
            'used_quota'  => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'Employee ID is required.',
            'year.required'        => 'Year is required.',
            'year.integer'         => 'Year must be a number.',
            'total_quota.integer'  => 'Total quota must be an integer.',
            'used_quota.integer'   => 'Used quota must be an integer.',
        ];
    }
}
