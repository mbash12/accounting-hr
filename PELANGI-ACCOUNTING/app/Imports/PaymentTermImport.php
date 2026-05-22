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
            if (isset($row['due_days']) && $row['due_days'] !== '' && $row['due_days'] !== null) {
                $cleanedValue = preg_replace('/[^0-9]/', '', trim((string) $row['due_days'], '"\' '));
                if ($cleanedValue !== '') {
                    $dueDays = (int) $cleanedValue;
                }
            }

            // Parse is_active
            $isActive = true;
            if (isset($row['active_status'])) {
                $statusValue = strtolower(trim((string) $row['active_status']));
                $isActive = in_array($statusValue, ['ya', 'true', '1', 'aktif', 'yes']);
            }

            $data = [
                'name' => isset($row['term_name']) ? (string) $row['term_name'] : null,
                'due_days' => $dueDays,
                'is_active' => $isActive,
                'description' => isset($row['description']) ? (string) $row['description'] : null,
                'created_by_user_id' => Auth::id(),
            ];

            // Check if code is provided, otherwise generate one
            $code = isset($row['term_code']) && !empty($row['term_code']) 
                ? (string) $row['term_code'] 
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
        // Parse active_status to boolean
        $isActive = true;
        if (isset($data['active_status'])) {
            $statusValue = strtolower(trim((string) $data['active_status']));
            $isActive = in_array($statusValue, ['ya', 'true', '1', 'aktif', 'yes']);
        }

        // Clean due_days (0 is a valid value)
        $jumlahHari = 0;
        if (isset($data['due_days']) && $data['due_days'] !== '' && $data['due_days'] !== null) {
            $cleaned = preg_replace('/[^0-9]/', '', trim((string) $data['due_days'], '"\' '));
            $jumlahHari = $cleaned !== '' ? (int) $cleaned : 0;
        }

        return [
            'term_code' => isset($data['term_code']) ? (string) $data['term_code'] : null,
            'term_name' => isset($data['term_name']) ? (string) $data['term_name'] : null,
            'due_days' => $jumlahHari,
            'active_status' => $isActive,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'term_code' => 'nullable|string|max:50',
            'term_name' => 'required|string|max:100',
            'due_days' => 'nullable|integer|min:0|max:3650',
            'active_status' => 'nullable|boolean',
            'description' => 'nullable|string|max:65535',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'term_code.max' => 'Payment Term Code cannot exceed 50 characters.',
            'term_name.required' => 'Payment Term Name is required.',
            'term_name.max' => 'Payment Term Name cannot exceed 100 characters.',
            'due_days.integer' => 'Due Days must be a whole number.',
            'due_days.min' => 'Due Days must be at least 0.',
            'due_days.max' => 'Due Days cannot exceed 3650 (10 years).',
            'description.max' => 'Description cannot exceed 65535 characters.',
        ];
    }

    /**
     * Get custom validation attributes
     */
    public function validationAttributes()
    {
        return [
            'term_code' => 'Payment Term Code',
            'term_name' => 'Payment Term Name',
            'due_days' => 'Due Days',
            'active_status' => 'Active Status',
            'description' => 'Description',
        ];
    }
}
