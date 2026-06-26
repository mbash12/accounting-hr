<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
            ? session('selected_company_id')
            : null;

        foreach ($rows as $row) {
            $departmentId = null;
            if (!empty($row['department_code'])) {
                $department = Department::where('code', (string) $row['department_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $departmentId = $department?->id;
            }

            $isActive = isset($row['active_status']) &&
                (strtolower((string) $row['active_status']) === 'yes' ||
                 strtolower((string) $row['active_status']) === 'true' ||
                 (string) $row['active_status'] === '1');

            $data = [
                'name'                        => (string) $row['name'],
                'email'                       => isset($row['email']) ? (string) $row['email'] : null,
                'nik'                         => isset($row['nik']) ? (string) $row['nik'] : null,
                'npwp'                        => isset($row['npwp']) ? (string) $row['npwp'] : null,
                'department_id'               => $departmentId,
                'position'                    => isset($row['position']) ? (string) $row['position'] : null,
                'hire_date'                   => isset($row['hire_date']) ? (string) $row['hire_date'] : null,
                'status'                      => isset($row['status']) ? (string) $row['status'] : 'permanent',
                'ptkp_status'                 => isset($row['ptkp_status']) ? (string) $row['ptkp_status'] : null,
                'bank_name'                   => isset($row['bank_name']) ? (string) $row['bank_name'] : null,
                'bank_account_number'         => isset($row['bank_account_number']) ? (string) $row['bank_account_number'] : null,
                'bank_account_holder'         => isset($row['bank_account_holder']) ? (string) $row['bank_account_holder'] : null,
                'bpjs_kesehatan_number'       => isset($row['bpjs_kesehatan_number']) ? (string) $row['bpjs_kesehatan_number'] : null,
                'bpjs_ketenagakerjaan_number' => isset($row['bpjs_ketenagakerjaan_number']) ? (string) $row['bpjs_ketenagakerjaan_number'] : null,
                'basic_salary'                => isset($row['basic_salary']) ? (float) $row['basic_salary'] : 0,
                'is_active'                   => $isActive,
                'created_by_user_id'          => Auth::id(),
            ];

            $employeeIdCode = isset($row['employee_id']) ? (string) $row['employee_id'] : null;

            if ($employeeIdCode) {
                $employee = Employee::where('employee_id', $employeeIdCode)
                    ->where('company_id', $companyId)
                    ->first();

                if ($employee) {
                    $employee->update($data);
                    continue;
                }

                $data['employee_id'] = $employeeIdCode;
            }

            $email = isset($row['email']) ? (string) $row['email'] : null;
            if ($email) {
                $employee = Employee::where('email', $email)
                    ->where('company_id', $companyId)
                    ->first();

                if ($employee) {
                    $employee->update($data);
                    continue;
                }
            }

            $data['company_id'] = $companyId;
            Employee::create($data);
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'employee_id'                 => isset($data['employee_id']) ? (string) $data['employee_id'] : null,
            'name'                        => isset($data['name']) ? (string) $data['name'] : null,
            'email'                       => isset($data['email']) ? (string) $data['email'] : null,
            'nik'                         => isset($data['nik']) ? (string) $data['nik'] : null,
            'npwp'                        => isset($data['npwp']) ? (string) $data['npwp'] : null,
            'department_code'             => isset($data['department_code']) ? (string) $data['department_code'] : null,
            'position'                    => isset($data['position']) ? (string) $data['position'] : null,
            'hire_date'                   => isset($data['hire_date']) ? (string) $data['hire_date'] : null,
            'status'                      => isset($data['status']) ? (string) $data['status'] : null,
            'ptkp_status'                 => isset($data['ptkp_status']) ? (string) $data['ptkp_status'] : null,
            'bank_name'                   => isset($data['bank_name']) ? (string) $data['bank_name'] : null,
            'bank_account_number'         => isset($data['bank_account_number']) ? (string) $data['bank_account_number'] : null,
            'bank_account_holder'         => isset($data['bank_account_holder']) ? (string) $data['bank_account_holder'] : null,
            'bpjs_kesehatan_number'       => isset($data['bpjs_kesehatan_number']) ? (string) $data['bpjs_kesehatan_number'] : null,
            'bpjs_ketenagakerjaan_number' => isset($data['bpjs_ketenagakerjaan_number']) ? (string) $data['bpjs_ketenagakerjaan_number'] : null,
            'basic_salary'                => isset($data['basic_salary']) ? $data['basic_salary'] : null,
            'active_status'               => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'nik'          => 'nullable|string|max:30',
            'hire_date'    => 'nullable|date',
            'status'       => 'nullable|in:permanent,contract,internship,probation',
            'basic_salary' => 'nullable|numeric|min:0',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required'    => 'Employee name is required.',
            'email.email'      => 'Email format is not valid.',
            'status.in'        => 'Status must be one of: permanent, contract, internship, probation.',
            'basic_salary.numeric' => 'Basic salary must be a number.',
        ];
    }
}
