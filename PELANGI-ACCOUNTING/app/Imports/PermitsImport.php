<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Permit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PermitsImport implements ToCollection, WithHeadingRow, WithValidation
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

            $data = [
                'employee_id'        => $employee->id,
                'type'               => (string) $row['type'],
                'start_date'         => (string) $row['start_date'],
                'end_date'           => isset($row['end_date']) ? (string) $row['end_date'] : (string) $row['start_date'],
                'reason'             => isset($row['reason']) ? (string) $row['reason'] : null,
                'status'             => isset($row['status']) ? (string) $row['status'] : 'pending',
                'created_by_user_id' => Auth::id(),
            ];

            $existing = Permit::where('employee_id', $employee->id)
                ->where('type', $data['type'])
                ->where('start_date', $data['start_date'])
                ->where('company_id', $companyId)
                ->first();

            if ($existing) {
                $existing->update($data);
            } else {
                $data['company_id'] = $companyId;
                Permit::create($data);
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'employee_id' => isset($data['employee_id']) ? (string) $data['employee_id'] : null,
            'type'        => isset($data['type']) ? (string) $data['type'] : null,
            'start_date'  => isset($data['start_date']) ? (string) $data['start_date'] : null,
            'end_date'    => isset($data['end_date']) ? (string) $data['end_date'] : null,
            'reason'      => isset($data['reason']) ? (string) $data['reason'] : null,
            'status'      => isset($data['status']) ? (string) $data['status'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string',
            'type'        => 'required|string|max:50',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'nullable|in:pending,approved,rejected',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'ID karyawan wajib diisi.',
            'type.required'        => 'Tipe izin wajib diisi.',
            'start_date.required'  => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'status.in'            => 'Status harus salah satu dari: pending, approved, rejected.',
        ];
    }
}
