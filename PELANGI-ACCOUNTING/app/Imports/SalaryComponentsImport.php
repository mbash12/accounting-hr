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
            $component = SalaryComponent::where('code', (string) $row['code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name'               => (string) $row['name'],
                'type'               => strtolower((string) $row['type']) === 'potongan' ? 'deduction' : 'allowance',
                'is_fixed'           => $this->parseBool($row['is_fixed'] ?? null),
                'is_taxable'         => $this->parseBool($row['is_taxable'] ?? null),
                'is_bpjs_base'       => $this->parseBool($row['is_bpjs_base'] ?? null),
                'is_active'          => $this->parseBool($row['is_active'] ?? 'yes'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($component) {
                $component->update($data);
            } else {
                $data['code']       = (string) $row['code'];
                $data['company_id'] = $companyId;
                SalaryComponent::create($data);
            }
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
            'is_active'    => isset($data['is_active']) ? (string) $data['is_active'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:200',
            'type' => 'required|string|in:allowance,deduction,tunjangan,potongan',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'code.required' => 'Component code is required.',
            'code.max'      => 'Component code must not exceed 50 characters.',
            'name.required' => 'Component name is required.',
            'type.required' => 'Component type is required.',
            'type.in'       => 'Component type must be "allowance", "deduction", "tunjangan", or "potongan".',
        ];
    }

    private function parseBool(mixed $value): bool
    {
        if ($value === null) return false;
        $v = strtolower(trim((string) $value));
        return in_array($v, ['yes', 'true', '1', 'ya']);
    }
}
