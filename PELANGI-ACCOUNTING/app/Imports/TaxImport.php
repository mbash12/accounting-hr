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
            if (!empty($row['akun_pembelian'])) {
                $account = Account::where('code', 'ilike', (string) $row['akun_pembelian'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$account) {
                    throw new \Exception("Account with code '{$row['akun_pembelian']}' not found in current company");
                }
                $purchaseAccountId = $account->id;
            } else {
                $purchaseAccountId = null; // Purchase account is optional
            }

            // Find sales account from account code
            if (!empty($row['akun_penjualan'])) {
                $account = Account::where('code', 'ilike', (string) $row['akun_penjualan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$account) {
                    throw new \Exception("Account with code '{$row['akun_penjualan']}' not found in current company");
                }
                $salesAccountId = $account->id;
            } else {
                $salesAccountId = null; // Sales account is optional
            }

            $tax = Tax::where('code', (string) $row['kode_pajak'])
                ->where('company_id', $companyId)
                ->first();

            // Clean the tax percentage value before converting to float
            $taxPercentage = 0;
            if (isset($row['persentase_pajak'])) {
                // Clean the value by removing any non-numeric characters except decimal point and minus
                $cleanedValue = preg_replace('/[^0-9.\-]/', '', trim((string) $row['persentase_pajak'], '"\' '));

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
                'name' => isset($row['nama_pajak']) ? (string) $row['nama_pajak'] : null,
                'tax_percentage' => $taxPercentage,
                'tax_type' => isset($row['jenis_pajak']) ? (string) $row['jenis_pajak'] : 'vat',
                'is_purchase_tax' => isset($row['pajak_pembelian']) &&
                    (strtolower((string) $row['pajak_pembelian']) === 'ya' ||
                        strtolower((string) $row['pajak_pembelian']) === 'true' ||
                        (string) $row['pajak_pembelian'] === '1'),
                'is_sales_tax' => isset($row['pajak_penjualan']) &&
                    (strtolower((string) $row['pajak_penjualan']) === 'ya' ||
                        strtolower((string) $row['pajak_penjualan']) === 'true' ||
                        (string) $row['pajak_penjualan'] === '1'),
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
                'is_active' => isset($row['status_aktif']) &&
                    (strtolower((string) $row['status_aktif']) === 'ya' ||
                        strtolower((string) $row['status_aktif']) === 'true' ||
                        (string) $row['status_aktif'] === '1'),
                'purchase_account_id' => $purchaseAccountId,
                'sales_account_id' => $salesAccountId,
                'created_by_user_id' => Auth::id(),
            ];

            if ($tax) {
                $tax->update($data);
            } else {
                $data['code'] = (string) $row['kode_pajak'];
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
        if (isset($data['pajak_pembelian'])) {
            $isPurchaseTax = \in_array(
                strtolower((string) $data['pajak_pembelian']),
                ['ya', 'true', '1']
            );
        }

        $isSalesTax = false;
        if (isset($data['pajak_penjualan'])) {
            $isSalesTax = \in_array(
                strtolower((string) $data['pajak_penjualan']),
                ['ya', 'true', '1']
            );
        }

        $isActive = false;
        if (isset($data['status_aktif'])) {
            $isActive = \in_array(
                strtolower((string) $data['status_aktif']),
                ['ya', 'true', '1']
            );
        }

        // Clean percentage value - remove any non-numeric characters except decimal point and minus
        $persentasePajak = null;
        if (isset($data['persentase_pajak'])) {
            // First, trim any quotes and whitespace
            $rawValue = trim((string) $data['persentase_pajak'], '"\' ');
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
            'nama_pajak' => isset($data['nama_pajak']) ? (string) $data['nama_pajak'] : null,
            'kode_pajak' => isset($data['kode_pajak']) ? (string) $data['kode_pajak'] : null,
            'persentase_pajak' => $persentasePajak,
            'jenis_pajak' => isset($data['jenis_pajak']) ? (string) $data['jenis_pajak'] : null,
            'pajak_pembelian' => $isPurchaseTax,
            'pajak_penjualan' => $isSalesTax,
            'status_aktif' => $isActive,
            'akun_pembelian' => isset($data['akun_pembelian']) ? (string) $data['akun_pembelian'] : null,
            'akun_penjualan' => isset($data['akun_penjualan']) ? (string) $data['akun_penjualan'] : null,
            'created_by_user_id' => Auth::check() ? Auth::id() : null,
        ];
    }


    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'nama_pajak' => 'required|string|max:200',
            'kode_pajak' => 'required|string|max:50',
            'persentase_pajak' => [
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
            'jenis_pajak' => 'required|in:vat,sales_tax,service_tax,withholding_tax,excise_tax',
            'pajak_pembelian' => 'nullable|boolean',
            'pajak_penjualan' => 'nullable|boolean',
            'status_aktif' => 'nullable|boolean',
            'akun_pembelian' => 'nullable|string|max:50',
            'akun_penjualan' => 'nullable|string|max:50',
            'created_by_user_id' => 'nullable|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_pajak.required' => 'Nama Pajak wajib diisi.',
            'nama_pajak.max' => 'Nama Pajak tidak boleh lebih dari 200 karakter.',
            'kode_pajak.required' => 'Kode Pajak wajib diisi.',
            'kode_pajak.max' => 'Kode Pajak tidak boleh lebih dari 50 karakter.',
            'persentase_pajak.required' => 'Persentase Pajak wajib diisi.',
            'jenis_pajak.required' => 'Jenis Pajak wajib diisi.',
            'jenis_pajak.in' => 'Jenis Pajak harus salah satu dari: vat, sales_tax, service_tax, withholding_tax, excise_tax.',
            'akun_pembelian.max' => 'Kode Akun Pembelian tidak boleh lebih dari 50 karakter.',
            'akun_penjualan.max' => 'Kode Akun Penjualan tidak boleh lebih dari 50 karakter.',
        ];
    }

    /**
     * Get custom validation attributes
     */
    public function validationAttributes()
    {
        return [
            'nama_pajak' => 'Nama Pajak',
            'kode_pajak' => 'Kode Pajak',
            'persentase_pajak' => 'Persentase Pajak',
            'jenis_pajak' => 'Jenis Pajak',
            'pajak_pembelian' => 'Pajak Pembelian',
            'pajak_penjualan' => 'Pajak Penjualan',
            'status_aktif' => 'Status Aktif',
            'akun_pembelian' => 'Akun Pembelian',
            'akun_penjualan' => 'Akun Penjualan',
        ];
    }
}