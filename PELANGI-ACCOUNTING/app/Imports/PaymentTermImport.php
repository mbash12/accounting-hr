<?php

namespace App\Imports;

use App\Models\PaymentTerm;
use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PaymentTermImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        // Get company ID from session or user's first company
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();
        
        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $companyId = $selectedCompanyId;
        } else {
            // Fallback to user's first company
            $companyId = $user?->companies()->first()?->id;
        }

        if (!$companyId) {
            throw new \Exception('No company selected. Please select a company before importing.');
        }

        foreach ($rows as $row) {
            // Clean the due_days value (0 is a valid value for cash/terms)
            $dueDays = 0;
            if (isset($row['jumlah_hari']) && $row['jumlah_hari'] !== '' && $row['jumlah_hari'] !== null) {
                $cleanedValue = preg_replace('/[^0-9]/', '', trim((string) $row['jumlah_hari'], '"\' '));
                if ($cleanedValue !== '') {
                    $dueDays = (int) $cleanedValue;
                }
            }

            // Parse is_active
            $isActive = true;
            if (isset($row['status_aktif'])) {
                $statusValue = strtolower(trim((string) $row['status_aktif']));
                $isActive = in_array($statusValue, ['ya', 'true', '1', 'aktif', 'yes']);
            }

            $data = [
                'name' => isset($row['nama_termin']) ? (string) $row['nama_termin'] : null,
                'due_days' => $dueDays,
                'is_active' => $isActive,
                'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                'created_by_user_id' => Auth::id(),
            ];

            // Check if code is provided, otherwise generate one
            $code = isset($row['kode_termin']) && !empty($row['kode_termin']) 
                ? (string) $row['kode_termin'] 
                : null;

            // Check if payment term exists by code within the same company
            if ($code) {
                $existingTerm = PaymentTerm::where('code', $code)
                    ->where('company_id', $companyId)
                    ->first();

                if ($existingTerm) {
                    // Update existing by code
                    $existingTerm->update($data);
                    continue;
                }
            }

            // Check if payment term exists by name (case-insensitive) within the same company
            $existingTermByName = PaymentTerm::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])
                ->where('company_id', $companyId)
                ->first();

            if ($existingTermByName) {
                $existingTermByName->update($data);
            } else {
                // Create new payment term
                $data['company_id'] = $companyId;
                $data['code'] = $code; // Will be auto-generated if null via model boot
                PaymentTerm::create($data);
            }
        }
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation($data, $index)
    {
        // Parse status_aktif to boolean
        $isActive = true;
        if (isset($data['status_aktif'])) {
            $statusValue = strtolower(trim((string) $data['status_aktif']));
            $isActive = in_array($statusValue, ['ya', 'true', '1', 'aktif', 'yes']);
        }

        // Clean jumlah_hari (0 is a valid value)
        $jumlahHari = 0;
        if (isset($data['jumlah_hari']) && $data['jumlah_hari'] !== '' && $data['jumlah_hari'] !== null) {
            $cleaned = preg_replace('/[^0-9]/', '', trim((string) $data['jumlah_hari'], '"\' '));
            $jumlahHari = $cleaned !== '' ? (int) $cleaned : 0;
        }

        return [
            'kode_termin' => isset($data['kode_termin']) ? (string) $data['kode_termin'] : null,
            'nama_termin' => isset($data['nama_termin']) ? (string) $data['nama_termin'] : null,
            'jumlah_hari' => $jumlahHari,
            'status_aktif' => $isActive,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'kode_termin' => 'nullable|string|max:50',
            'nama_termin' => 'required|string|max:100',
            'jumlah_hari' => 'nullable|integer|min:0|max:3650',
            'status_aktif' => 'nullable|boolean',
            'deskripsi' => 'nullable|string|max:65535',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_termin.max' => 'Kode Termin tidak boleh lebih dari 50 karakter.',
            'nama_termin.required' => 'Nama Termin wajib diisi.',
            'nama_termin.max' => 'Nama Termin tidak boleh lebih dari 100 karakter.',
            'jumlah_hari.integer' => 'Jumlah Hari harus berupa angka.',
            'jumlah_hari.min' => 'Jumlah Hari minimal 0.',
            'jumlah_hari.max' => 'Jumlah Hari maksimal 3650 (10 tahun).',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 65535 karakter.',
        ];
    }

    /**
     * Get custom validation attributes
     */
    public function validationAttributes()
    {
        return [
            'kode_termin' => 'Kode Termin',
            'nama_termin' => 'Nama Termin',
            'jumlah_hari' => 'Jumlah Hari',
            'status_aktif' => 'Status Aktif',
            'deskripsi' => 'Deskripsi',
        ];
    }
}
