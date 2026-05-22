<?php

namespace App\Imports;

use App\Models\Tax;
use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TaxImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors, then convert back as needed
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            // Find purchase account from account code
            if (!empty($row['purchase_account'])) {
                $account = Account::where('code', 'ilike', (string) $row['purchase_account'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$account) {
                    throw new \Exception("Account with code '{$row['purchase_account']}' not found in current company");
                }
                $purchaseAccountId = $account->id;
            } else {
                $purchaseAccountId = null; // Purchase account is optional
            }

            // Find sales account from account code
            if (!empty($row['sales_account'])) {
                $account = Account::where('code', 'ilike', (string) $row['sales_account'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$account) {
                    throw new \Exception("Account with code '{$row['sales_account']}' not found in current company");
                }
                $salesAccountId = $account->id;
            } else {
                $salesAccountId = null; // Sales account is optional
            }

            $tax = Tax::where('code', (string) $row['tax_code'])
                ->where('company_id', $companyId)
                ->first();

            // Clean the tax percentage value before converting to float
            $taxPercentage = 0;
            if (isset($row['tax_percentage'])) {
                // Clean the value by removing any non-numeric characters except decimal point and minus
                $cleanedValue = preg_replace('/[^0-9.\-]/', '', trim((string) $row['tax_percentage'], '"\' '));

                // Handle edge case: if value starts with decimal point, prepend 0
                if ($cleanedValue !== '' && $cleanedValue[0] === '.') {
                    $cleanedValue = '0' . $cleanedValue;
                }
                // Handle edge case: if value starts with minus and decimal point, prepend 0
                if (strlen($cleanedValue) > 1 && $cleanedValue[0] === '-' && $cleanedValue[1] === '.') {
                    $cleanedValue = '-0' . substr($cleanedValue, 1);
                }

                if ($cleanedValue !== '') {
                    $taxPercentage = (float) $cleanedValue;
                }
            }

            $data = [
                'name' => isset($row['tax_name']) ? (string) $row['tax_name'] : null,
                'tax_percentage' => $taxPercentage,
                'tax_type' => isset($row['tax_type']) ? (string) $row['tax_type'] : 'vat',
                'is_purchase_tax' => isset($row['purchase_tax']) &&
                    (strtolower((string) $row['purchase_tax']) === 'ya' ||
                        strtolower((string) $row['purchase_tax']) === 'true' ||
                        (string) $row['purchase_tax'] === '1'),
                'is_sales_tax' => isset($row['sales_tax']) &&
                    (strtolower((string) $row['sales_tax']) === 'ya' ||
                        strtolower((string) $row['sales_tax']) === 'true' ||
                        (string) $row['sales_tax'] === '1'),
                'effective_date' => isset($row['tanggal_berlaku']) ?
                    (\DateTime::createFromFormat('Y-m-d', (string) $row['tanggal_berlaku']) ?:
                        \DateTime::createFromFormat('d/m/Y', (string) $row['tanggal_berlaku'])) :
                    date('Y-m-d'),
                'expiry_date' => isset($row['tanggal_kadaluarsa']) && !empty($row['tanggal_kadaluarsa']) ?
                    (\DateTime::createFromFormat('Y-m-d', (string) $row['tanggal_kadaluarsa']) ?:
                        \DateTime::createFromFormat('d/m/Y', (string) $row['tanggal_kadaluarsa'])) :
                    null,
                'compound_tax' => isset($row['pajak_majemuk']) &&
                    (strtolower((string) $row['pajak_majemuk']) === 'ya' ||
                        strtolower((string) $row['pajak_majemuk']) === 'true' ||
                        (string) $row['pajak_majemuk'] === '1'),
                'is_active' => isset($row['active_status']) &&
                    (strtolower((string) $row['active_status']) === 'ya' ||
                        strtolower((string) $row['active_status']) === 'true' ||
                        (string) $row['active_status'] === '1'),
                'purchase_account_id' => $purchaseAccountId,
                'sales_account_id' => $salesAccountId,
                'created_by_user_id' => Auth::id(),
            ];

            if ($tax) {
                $tax->update($data);
            } else {
                $data['code'] = (string) $row['tax_code'];
                $data['company_id'] = $companyId;
                Tax::create($data);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        // Convert boolean fields from text to boolean before validation
        $isPurchaseTax = false;
        if (isset($data['purchase_tax'])) {
            $isPurchaseTax = \in_array(
                strtolower((string) $data['purchase_tax']),
                ['ya', 'true', '1']
            );
        }

        $isSalesTax = false;
        if (isset($data['sales_tax'])) {
            $isSalesTax = \in_array(
                strtolower((string) $data['sales_tax']),
                ['ya', 'true', '1']
            );
        }

        $isActive = false;
        if (isset($data['active_status'])) {
            $isActive = \in_array(
                strtolower((string) $data['active_status']),
                ['ya', 'true', '1']
            );
        }

        // Clean percentage value - remove any non-numeric characters except decimal point and minus
        $persentasePajak = null;
        if (isset($data['tax_percentage'])) {
            // First, trim any quotes and whitespace
            $rawValue = trim((string) $data['tax_percentage'], '"\' ');
            // Remove any non-numeric characters except decimal point and minus
            $cleaned = preg_replace('/[^0-9.\-]/', '', $rawValue);
            // Handle edge case: if value starts with decimal point, prepend 0
            if ($cleaned !== '' && $cleaned[0] === '.') {
                $cleaned = '0' . $cleaned;
            }
            // Handle edge case: if value starts with minus and decimal point, prepend 0
            if (strlen($cleaned) > 1 && $cleaned[0] === '-' && $cleaned[1] === '.') {
                $cleaned = '-0' . substr($cleaned, 1);
            }
            $persentasePajak = $cleaned;
        }

        return [
            'tax_name' => isset($data['tax_name']) ? (string) $data['tax_name'] : null,
            'tax_code' => isset($data['tax_code']) ? (string) $data['tax_code'] : null,
            'tax_percentage' => $persentasePajak,
            'tax_type' => isset($data['tax_type']) ? (string) $data['tax_type'] : null,
            'purchase_tax' => $isPurchaseTax,
            'sales_tax' => $isSalesTax,
            'active_status' => $isActive,
            'purchase_account' => isset($data['purchase_account']) ? (string) $data['purchase_account'] : null,
            'sales_account' => isset($data['sales_account']) ? (string) $data['sales_account'] : null,
            'created_by_user_id' => Auth::check() ? Auth::id() : null,
        ];
    }


    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'tax_name' => 'required|string|max:200',
            'tax_code' => 'required|string|max:50',
            'tax_percentage' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Clean the value by removing any non-numeric characters except decimal point and minus
                    $cleanedValue = preg_replace('/[^0-9.\-]/', '', trim($value, '"\' '));

                    // Handle edge case: if value starts with decimal point, prepend 0
                    if ($cleanedValue !== '' && $cleanedValue[0] === '.') {
                        $cleanedValue = '0' . $cleanedValue;
                    }
                    // Handle edge case: if value starts with minus and decimal point, prepend 0
                    if (strlen($cleanedValue) > 1 && $cleanedValue[0] === '-' && $cleanedValue[1] === '.') {
                        $cleanedValue = '-0' . substr($cleanedValue, 1);
                    }

                    if ($cleanedValue === '') {
                        $fail('The :attribute must be a valid number.');
                        return;
                    }

                    $numericValue = (float) $cleanedValue;
                    if ($numericValue < 0 || $numericValue > 100) {
                        $fail('The :attribute must be between 0 and 100.');
                    }
                }
            ],
            'tax_type' => 'required|in:vat,sales_tax,service_tax,withholding_tax,excise_tax',
            'purchase_tax' => 'nullable|boolean',
            'sales_tax' => 'nullable|boolean',
            'active_status' => 'nullable|boolean',
            'purchase_account' => 'nullable|string|max:50',
            'sales_account' => 'nullable|string|max:50',
            'created_by_user_id' => 'nullable|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tax_name.required' => 'Tax Name is required.',
            'tax_name.max' => 'Tax Name cannot exceed 200 characters.',
            'tax_code.required' => 'Tax Code is required.',
            'tax_code.max' => 'Tax Code cannot exceed 50 characters.',
            'tax_percentage.required' => 'Tax Percentage is required.',
            'tax_type.required' => 'Tax Type is required.',
            'tax_type.in' => 'Tax Type must be one of: vat, sales_tax, service_tax, withholding_tax, excise_tax.',
            'purchase_account.max' => 'Purchase Account Code cannot exceed 50 characters.',
            'sales_account.max' => 'Sales Account Code cannot exceed 50 characters.',
        ];
    }

    /**
     * Get custom validation attributes
     */
    public function validationAttributes()
    {
        return [
            'tax_name' => 'Tax Name',
            'tax_code' => 'Tax Code',
            'tax_percentage' => 'Tax Percentage',
            'tax_type' => 'Tax Type',
            'purchase_tax' => 'Purchase Tax',
            'sales_tax' => 'Sales Tax',
            'active_status' => 'Active Status',
            'purchase_account' => 'Purchase Account',
            'sales_account' => 'Sales Account',
        ];
    }
}