<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeesTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                '',
                'Budi Santoso',
                'budi@email.com',
                '1234567890123456',
                '12.345.678.9-012.345',
                'HR',
                'Staff HR',
                '2023-01-15',
                'permanent',
                'TK/0',
                'BCA',
                '1234567890',
                'Budi Santoso',
                '',
                '',
                5000000,
                'yes',
            ],
            [
                '',
                'Siti Rahma',
                'siti@email.com',
                '9876543210987654',
                '',
                'IT',
                'Developer',
                '2023-03-01',
                'contract',
                'K/1',
                'Mandiri',
                '0987654321',
                'Siti Rahma',
                '',
                '',
                7000000,
                'yes',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'name',
            'email',
            'nik',
            'npwp',
            'department_code',
            'position',
            'hire_date',
            'status',
            'ptkp_status',
            'bank_name',
            'bank_account_number',
            'bank_account_holder',
            'bpjs_kesehatan_number',
            'bpjs_ketenagakerjaan_number',
            'basic_salary',
            'active_status',
        ];
    }

    public function title(): string
    {
        return 'Employees Import Template';
    }
}
