<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $contact = Contact::where('contact_code', (string) $row['contact_code'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'name' => isset($row['name']) ? (string) $row['name'] : null,
                'phone' => isset($row['phone']) ? (string) $row['phone'] : null,
                'contact_code' => isset($row['contact_code']) ? (string) $row['contact_code'] : null,
                'contact_person' => isset($row['contact_person']) ? (string) $row['contact_person'] : null,
                'email' => isset($row['email']) ? (string) $row['email'] : null,
                'is_customer' => isset($row['is_customer']) ? $this->parseBoolean($row['is_customer']) : false,
                'is_supplier' => isset($row['is_supplier']) ? $this->parseBoolean($row['is_supplier']) : false,
                'is_employee' => isset($row['is_employee']) ? $this->parseBoolean($row['is_employee']) : false,
                'is_active' => isset($row['is_active']) ? $this->parseBoolean($row['is_active']) : true,
                'billing_address_line_1' => isset($row['billing_address_line_1']) ? (string) $row['billing_address_line_1'] : null,
                'delivery_address_line_1' => isset($row['delivery_address_line_1']) ? (string) $row['delivery_address_line_1'] : null,
                'tax' => isset($row['tax']) ? (string) $row['tax'] : null,
                'is_pkp' => isset($row['is_pkp']) ? $this->parseBoolean($row['is_pkp']) : false,
                'created_by_user_id' => Auth::id(),
            ];

            if ($contact) {
                $contact->update($data);
            } else {
                $data['email'] = (string) $row['email'];
                $data['company_id'] = $companyId;
                Contact::create($data);
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
            'name' => isset($data['name']) ? (string) $data['name'] : null,
            'email' => isset($data['email']) ? (string) $data['email'] : null,
            'phone' => isset($data['phone']) ? (string) $data['phone'] : null,
            'contact_code' => isset($data['contact_code']) ? (string) $data['contact_code'] : null,
            'contact_person' => isset($data['contact_person']) ? (string) $data['contact_person'] : null,
            'is_customer' => isset($data['is_customer']) ? (string) $data['is_customer'] : null,
            'is_supplier' => isset($data['is_supplier']) ? (string) $data['is_supplier'] : null,
            'is_employee' => isset($data['is_employee']) ? (string) $data['is_employee'] : null,
            'is_active' => isset($data['is_active']) ? (string) $data['is_active'] : null,
            'billing_address_line_1' => isset($data['billing_address_line_1']) ? (string) $data['billing_address_line_1'] : null,
            'delivery_address_line_1' => isset($data['delivery_address_line_1']) ? (string) $data['delivery_address_line_1'] : null,
            'tax' => isset($data['tax']) ? (string) $data['tax'] : null,
            'is_pkp' => isset($data['is_pkp']) ? (string) $data['is_pkp'] : null,
            'created_by_user_id' => isset($data['created_by_user_id']) ? (string) $data['created_by_user_id'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'name' => 'required|string|max:200',
            'email' => 'nullable|email|max:150|unique:contacts,email,NULL,id,company_id,' . ($companyId ?? 'NULL') . ',deleted_at,NULL',
            'phone' => 'nullable|string|max:255',
            'contact_code' => 'required|string|max:50',
            'contact_person' => 'nullable|string|max:200',
            'is_customer' => 'nullable|string|in:1,0,true,false,yes,no,1,0,active,non-active,aktif,non-aktif,ya,tidak',
            'is_supplier' => 'nullable|string|in:1,0,true,false,yes,no,1,0,active,non-active,aktif,non-aktif,ya,tidak',
            'is_employee' => 'nullable|string|in:1,0,true,false,yes,no,1,0,active,non-active,aktif,non-aktif,ya,tidak',
            'is_active' => 'nullable|string|in:1,0,true,false,yes,no,1,0,active,non-active,aktif,non-aktif,ya,tidak',
            'billing_address_line_1' => 'nullable|string|max:1000',
            'delivery_address_line_1' => 'nullable|string|max:1000',
            'tax' => 'nullable|string|max:50',
            'is_pkp' => 'nullable|string|in:1,0,true,false,yes,no,1,0,active,non-active,aktif,non-aktif,ya,tidak',
            'created_by_user_id' => 'nullable|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Contact Name is required.',
            'name.max' => 'Contact Name cannot exceed 200 characters.',
            'contact_code.required' => 'Contact Code is required.',
            'contact_code.max' => 'Contact Code cannot exceed 50 characters.',
            'email.email' => 'Email format is not valid.',
            'email.max' => 'Email cannot exceed 150 characters.',
            'email.unique' => 'Email is already in use.',
            'contact_person.max' => 'Contact Person Name cannot exceed 200 characters.',
            'billing_address_line_1.max' => 'Billing Address cannot exceed 1000 characters.',
            'delivery_address_line_1.max' => 'Delivery Address cannot exceed 1000 characters.',
            'tax.max' => 'Tax ID cannot exceed 50 characters.',
        ];
    }

    private function parseBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $strValue = strtolower(trim((string) $value));

        return in_array($strValue, ['1', 'true', 'yes', 'ya', 'on', 'active', 'enabled']);
    }
}