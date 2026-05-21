<?php

namespace App\Imports;

use App\Models\ProductGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductGroupsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert all values to strings to avoid type errors
            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

            $productGroup = ProductGroup::where('code', (string) $row['kode_grup_produk'])
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'code' => (string) $row['kode_grup_produk'],
                'shipping_type' => (string) $row['tipe_pengiriman'],
                'is_active' => isset($row['status_aktif']) &&
                    (strtolower((string) $row['status_aktif']) === 'ya' ||
                        strtolower((string) $row['status_aktif']) === 'yes' ||
                        strtolower((string) $row['status_aktif']) === 'true' ||
                        (string) $row['status_aktif'] === '1'),
                'created_by_user_id' => Auth::id(),
            ];

            if ($productGroup) {
                $productGroup->update($data);
            } else {
                $data['name'] = (string) $row['nama_grup_produk'];
                $data['company_id'] = $companyId;
                ProductGroup::create($data);
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
            'nama_grup_produk' => isset($data['nama_grup_produk']) ? (string) $data['nama_grup_produk'] : null,
            'kode_grup_produk' => isset($data['kode_grup_produk']) ? (string) $data['kode_grup_produk'] : null,
            'tipe_pengiriman' => isset($data['tipe_pengiriman']) ? (string) $data['tipe_pengiriman'] : null,
            'status_aktif' => isset($data['status_aktif']) ? (string) $data['status_aktif'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'nama_grup_produk' => 'required|string|max:255',
            'kode_grup_produk' => 'required|string|max:255',
            'tipe_pengiriman' => 'required|string|in:physical,digital',
            'status_aktif' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_grup_produk.required' => 'Product Group Name is required.',
            'nama_grup_produk.max' => 'Product Group Name cannot exceed 255 characters.',
            'kode_grup_produk.required' => 'Product Group Code is required.',
            'kode_grup_produk.max' => 'Product Group Code cannot exceed 255 characters.',
            'tipe_pengiriman.required' => 'Shipping Type is required.',
            'tipe_pengiriman.in' => 'Shipping Type must be "physical" or "digital".',
        ];
    }
}