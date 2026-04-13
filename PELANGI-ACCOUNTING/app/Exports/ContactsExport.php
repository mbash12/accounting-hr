<?php

namespace App\Exports;

use App\Models\Contact;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ContactsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Contact::select([
                'name', 'email', 'phone', 'contact_code', 'contact_person',
                'is_customer', 'is_supplier', 'is_employee', 'is_sales', 'credit_limit',
                'is_active', 'billing_address_line_1', 'delivery_address_line_1', 'tax', 'is_pkp'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($contact) {
                return [
                    'contact_code' => $contact->contact_code,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'contact_person' => $contact->contact_person,
                    'is_customer' => $contact->is_customer ? 'ya' : 'tidak',
                    'is_supplier' => $contact->is_supplier ? 'ya' : 'tidak',
                    'is_employee' => $contact->is_employee ? 'ya' : 'tidak',
                    'is_sales' => $contact->is_sales ? 'ya' : 'tidak',
                    'is_pkp' => $contact->is_pkp ? 'ya' : 'tidak',
                    'billing_address' => $contact->billing_address_line_1,
                    'shipping_address' => $contact->delivery_address_line_1,
                    'is_active' => $contact->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Kontak',
            'Nama Kontak',
            'Email',
            'Telepon',
            'Nama Kontak',
            'Pelanggan',
            'Pemasok',
            'Karyawan',
            'Penjualan',
            'PKP',
            'Alamat Tagihan',
            'Alamat Pengiriman',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Kontak';
    }
}