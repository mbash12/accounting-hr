<?php

namespace App\Imports;

use App\Models\SalaryComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryComponentsImport implements ToCollection, WithHeadingRow, WithValidation
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

            $data = [
                'name'         => (string) $row['name'],
                'type'         => (string) $row['type'],
                'is_fixed'     => $toBool($row['is_fixed'] ?? null),
                'is_taxable'   => $toBool($row['is_taxable'] ?? null),
                'is_bpjs_base' => $toBool($row['is_bpjs_base'] ?? null),
                'is_active'    => $toBool($row['active_status'] ?? null),
                'created_by_user_id' => Auth::id(),
            ];

            $code = isset($row['code']) ? (string) $row['code'] : null;

            if ($code) {
                $existing = SalaryComponent::where('code', $code)
                    ->where('company_id', $companyId)
                    ->first();

                if ($existing) {
                    $existing->update($data);
                    continue;
                }

                $data['code'] = $code;
            }

            $data['company_id'] = $companyId;
            SalaryComponent::create($data);
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'code'         => isset($data['code']) ? (string) $data['code'] : null,
            'name'         => isset($data['name']) ? (string) $data['name'] : null,
            'type'         => isset($data['type']) ? (string) $data['type'] : null,
            'is_fixed'     => isset($data['is_fixed']) ? (string) $data['is_fixed'] : null,
            'is_taxable'   => isset($data['is_taxable']) ? (string) $data['is_taxable'] : null,
            'is_bpjs_base' => isset($data['is_bpjs_base']) ? (string) $data['is_bpjs_base'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:allowance,deduction',
            'is_fixed'     => 'nullable|string',
            'is_taxable'   => 'nullable|string',
            'is_bpjs_base' => 'nullable|string',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama komponen gaji wajib diisi.',
            'type.required' => 'Tipe komponen gaji wajib diisi.',
            'type.in'       => 'Tipe harus salah satu dari: allowance, deduction.',
        ];
    }
}
