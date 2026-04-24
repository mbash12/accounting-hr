<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendancesImport implements ToCollection, WithHeadingRow, WithValidation
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

            $existing = Attendance::where('employee_id', $employee->id)
                ->where('date', (string) $row['date'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'employee_id'             => $employee->id,
                'date'                    => (string) $row['date'],
                'check_in'                => !empty($row['check_in']) ? (string) $row['date'] . ' ' . (string) $row['check_in'] : null,
                'check_out'               => !empty($row['check_out']) ? (string) $row['date'] . ' ' . (string) $row['check_out'] : null,
                'late_minutes'            => isset($row['late_minutes']) ? (int) $row['late_minutes'] : 0,
                'early_departure_minutes' => isset($row['early_departure_minutes']) ? (int) $row['early_departure_minutes'] : 0,
                'status'                  => isset($row['status']) ? (string) $row['status'] : 'present',
                'notes'                   => isset($row['notes']) ? (string) $row['notes'] : null,
                'notes_in'                => isset($row['notes_in']) ? (string) $row['notes_in'] : (isset($row['notes']) ? (string) $row['notes'] : null),
                'notes_out'               => isset($row['notes_out']) ? (string) $row['notes_out'] : null,
                'created_by_user_id'      => Auth::id(),
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $data['company_id'] = $companyId;
                Attendance::create($data);
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'employee_id'             => isset($data['employee_id']) ? (string) $data['employee_id'] : null,
            'date'                    => isset($data['date']) ? (string) $data['date'] : null,
            'check_in'                => isset($data['check_in']) ? (string) $data['check_in'] : null,
            'check_out'               => isset($data['check_out']) ? (string) $data['check_out'] : null,
            'late_minutes'            => isset($data['late_minutes']) ? $data['late_minutes'] : null,
            'early_departure_minutes' => isset($data['early_departure_minutes']) ? $data['early_departure_minutes'] : null,
            'status'                  => isset($data['status']) ? (string) $data['status'] : null,
            'notes'                   => isset($data['notes']) ? (string) $data['notes'] : null,
            'notes_in'                => isset($data['notes_in']) ? (string) $data['notes_in'] : null,
            'notes_out'               => isset($data['notes_out']) ? (string) $data['notes_out'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string',
            'date'        => 'required|date',
            'status'      => 'nullable|in:present,late,absent,permit,leave',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'ID karyawan wajib diisi.',
            'date.required'        => 'Tanggal wajib diisi.',
            'date.date'            => 'Format tanggal tidak valid.',
            'status.in'            => 'Status harus salah satu dari: present, late, absent, permit, leave.',
        ];
    }
}
