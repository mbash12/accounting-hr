<?php

namespace App\Imports;

use App\Models\Bank;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BanksImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $bank = Bank::where('code', (string) $row['bank_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => (string) $row['bank_name'],
                'country' => $row['country'] ? (string) $row['country'] : null,
                'clearing_code' => $row['clearing_code'] ? (string) $row['clearing_code'] : null,
                'skn_code' => $row['skn_code'] ? (string) $row['skn_code'] : null,
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'yes' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'created_by_user_id' => Auth::check() ? Auth::id() : session('current_user_id'),
            ];

            if ($bank) {
                $bank->update($data);
            } else {
                $data['code'] = (string) $row['bank_code'];
                $data['company_id'] = $companyId;
                Bank::create($data);
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
            'bank_code' => isset($data['bank_code']) ? (string) $data['bank_code'] : null,
            'bank_name' => isset($data['bank_name']) ? (string) $data['bank_name'] : null,
            'country' => isset($data['country']) ? (string) $data['country'] : null,
            'clearing_code' => isset($data['clearing_code']) ? (string) $data['clearing_code'] : null,
            'skn_code' => isset($data['skn_code']) ? (string) $data['skn_code'] : null,
            'active_status' => isset($data['active_status']) ? (string) $data['active_status'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'bank_code' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'clearing_code' => 'nullable|string|max:50',
            'skn_code' => 'nullable|string|max:50',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'bank_code.required' => 'Kode Bank wajib diisi.',
            'bank_code.max' => 'Kode Bank tidak boleh lebih dari 20 karakter.',
            'bank_name.required' => 'Nama Bank wajib diisi.',
            'bank_name.max' => 'Nama Bank tidak boleh lebih dari 255 karakter.',
        ];
    }
}