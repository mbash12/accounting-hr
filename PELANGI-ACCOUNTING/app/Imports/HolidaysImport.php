<?php

namespace App\Imports;

use App\Models\Holiday;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HolidaysImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
            ? session('selected_company_id')
            : null;

        foreach ($rows as $row) {
            $isCutiBersama = isset($row['is_cuti_bersama']) &&
                (strtolower((string) $row['is_cuti_bersama']) === 'yes' ||
                 strtolower((string) $row['is_cuti_bersama']) === 'true' ||
                 (string) $row['is_cuti_bersama'] === '1');

            $existing = Holiday::where('date', (string) $row['date'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name'            => (string) $row['name'],
                'is_cuti_bersama' => $isCutiBersama,
                'created_by_user_id' => Auth::id(),
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $data['date'] = (string) $row['date'];
                $data['company_id'] = $companyId;
                Holiday::create($data);
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'name'            => isset($data['name']) ? (string) $data['name'] : null,
            'date'            => isset($data['date']) ? (string) $data['date'] : null,
            'is_cuti_bersama' => isset($data['is_cuti_bersama']) ? (string) $data['is_cuti_bersama'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_cuti_bersama' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama hari libur wajib diisi.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date'     => 'Format tanggal tidak valid.',
        ];
    }
}
