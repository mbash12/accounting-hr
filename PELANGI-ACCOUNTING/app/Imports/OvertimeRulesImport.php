<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\OvertimeRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OvertimeRulesImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
            ? session('selected_company_id')
            : null;

        foreach ($rows as $row) {
            $toBool = fn ($val) => strtolower((string) ($val ?? '')) === 'yes' ||
                strtolower((string) ($val ?? '')) === 'true' ||
                (string) ($val ?? '') === '1';

            $departmentId = null;
            if (!empty($row['department_code'])) {
                $department = Department::where('code', (string) $row['department_code'])
                    ->where('company_id', $companyId)
                    ->first();
                $departmentId = $department?->id;
            }

            $data = [
                'name'                               => (string) $row['name'],
                'department_id'                      => $departmentId,
                'is_default'                         => $toBool($row['is_default'] ?? null),
                'base_hourly_rate_divisor'           => isset($row['base_hourly_rate_divisor']) ? (float) $row['base_hourly_rate_divisor'] : 173,
                'workday_first_hour_multiplier'      => isset($row['workday_first_hour_multiplier']) ? (float) $row['workday_first_hour_multiplier'] : 1.5,
                'workday_subsequent_hour_multiplier' => isset($row['workday_subsequent_hour_multiplier']) ? (float) $row['workday_subsequent_hour_multiplier'] : 2,
                'holiday_multiplier'                 => isset($row['holiday_multiplier']) ? (float) $row['holiday_multiplier'] : 2,
                'is_active'                          => $toBool($row['active_status'] ?? null),
                'created_by_user_id'                 => Auth::id(),
            ];

            $existing = OvertimeRule::where('name', $data['name'])
                ->where('company_id', $companyId)
                ->first();

            if ($existing) {
                $existing->update($data);
            } else {
                $data['company_id'] = $companyId;
                OvertimeRule::create($data);
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'name'                               => isset($data['name']) ? (string) $data['name'] : null,
            'department_code'                    => isset($data['department_code']) ? (string) $data['department_code'] : null,
            'is_default'                         => isset($data['is_default']) ? (string) $data['is_default'] : null,
            'base_hourly_rate_divisor'           => isset($data['base_hourly_rate_divisor']) ? $data['base_hourly_rate_divisor'] : null,
            'workday_first_hour_multiplier'      => isset($data['workday_first_hour_multiplier']) ? $data['workday_first_hour_multiplier'] : null,
            'workday_subsequent_hour_multiplier' => isset($data['workday_subsequent_hour_multiplier']) ? $data['workday_subsequent_hour_multiplier'] : null,
            'holiday_multiplier'                 => isset($data['holiday_multiplier']) ? $data['holiday_multiplier'] : null,
            'active_status'                      => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'name'                               => 'required|string|max:255',
            'base_hourly_rate_divisor'           => 'nullable|numeric|min:1',
            'workday_first_hour_multiplier'      => 'nullable|numeric|min:0',
            'workday_subsequent_hour_multiplier' => 'nullable|numeric|min:0',
            'holiday_multiplier'                 => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama aturan lembur wajib diisi.',
        ];
    }
}
