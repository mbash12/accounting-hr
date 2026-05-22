<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ContactsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                'John Doe',
                'john.doe@example.com',
                '+62-21-5555',
                'JOHN-001',
                'John Doe (Sales Representative)',
                'yes',
                'no',
                'no',
                'yes',
                'no',
                'Jl. Sudirman No. 123, Jakarta Selatan, DKI Jakarta 12345',
                'Jl. Gatot Subroto No. 456, Jakarta Pusat, DKI Jakarta 67890',
                'NPWP-1234567890',
            ],
            [
                'Jane Smith',
                'jane.smith@example.com',
                '+62-22-8877',
                'JANE-002',
                'Jane Smith (Purchasing Manager)',
                'yes',
                'no',
                'yes',
                'yes',
                'yes',
                'Jl. Siti Nurhalizah No. 789, Surabaya, Jatim 54321',
                'Jl. Ahmad Yani No. 111, Surabaya, Jatim 98765',
                'NPWP-0987654321',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'phone',
            'contact_code',
            'contact_person',
            'is_customer',
            'is_supplier',
            'is_employee',
            'is_active',
            'is_pkp',
            'billing_address_line_1',
            'delivery_address_line_1',
            'tax'
        ];
    }

    public function title(): string
    {
        return 'Contact Import Template';
    }
}
