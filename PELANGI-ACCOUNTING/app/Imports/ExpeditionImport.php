<?php

namespace App\Imports;

use App\Models\Expedition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ExpeditionImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $expedition = Expedition::where('code', (string) $row['code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => isset($row['name']) ? (string) $row['name'] : null,
                'is_active' => isset($row['is_active']) &&
                    $this->parseBooleanValue(trim((string) $row['is_active'])),
                'created_by_user_id' => Auth::id(),
            ];

            if ($expedition) {
                $expedition->update($data);
            } else {
                $data['code'] = (string) $row['code'];
                $data['company_id'] = $companyId;
                Expedition::create($data);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        // Convert all fields to strings to satisfy validation rules and trim whitespace
        return [
            'name' => isset($data['name']) ? trim((string) $data['name']) : null,
            'code' => isset($data['code']) ? trim((string) $data['code']) : null,
            'is_active' => isset($data['is_active']) ? trim((string) $data['is_active']) : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50',
            'is_active' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Expedition Name is required.',
            'name.max' => 'Expedition Name cannot exceed 200 characters.',
            'code.required' => 'Expedition Code is required.',
            'code.max' => 'Expedition Code cannot exceed 50 characters.',
            'code.unique' => 'Expedition Code is already in use.',
            'company_name.max' => 'Company Name cannot exceed 200 characters.',
        ];
    }

    /**
     * Parse various string representations to boolean
     */
    private function parseBooleanValue($value)
    {
        if (is_null($value) || $value === '') {
            return false;
        }

        $lowerValue = strtolower(trim($value));

        // Truthy values
        $trueValues = [
            '1', 'yes', 'ya', 'true', 'active', 'on', 'enable', 'enabled',
            'y', 't', 'aktif', '2'
        ];

        return in_array($lowerValue, $trueValues) || $value === '1' || $value === '2';
    }
}