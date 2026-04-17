<?php

namespace App\Exports;

use App\Models\Employee;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeesExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Employee::with('department');

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()->map(function ($employee) {
            return [
                'employee_id'                  => $employee->employee_id,
                'name'                         => $employee->name,
                'email'                        => $employee->email,
                'nik'                          => $employee->nik,
                'npwp'                         => $employee->npwp,
                'department_code'              => $employee->department?->code,
                'position'                     => $employee->position,
                'hire_date'                    => $employee->hire_date?->format('Y-m-d'),
                'status'                       => $employee->status,
                'ptkp_status'                  => $employee->ptkp_status,
                'bank_name'                    => $employee->bank_name,
                'bank_account_number'          => $employee->bank_account_number,
                'bank_account_holder'          => $employee->bank_account_holder,
                'bpjs_kesehatan_number'        => $employee->bpjs_kesehatan_number,
                'bpjs_ketenagakerjaan_number'  => $employee->bpjs_ketenagakerjaan_number,
                'basic_salary'                 => $employee->basic_salary,
                'active_status'                => $employee->is_active ? 'yes' : 'no',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Karyawan',
            'Nama',
            'Email',
            'NIK',
            'NPWP',
            'Kode Departemen',
            'Jabatan',
            'Tanggal Mulai Kerja',
            'Status',
            'Status PTKP',
            'Nama Bank',
            'Nomor Rekening',
            'Pemilik Rekening',
            'No BPJS Kesehatan',
            'No BPJS Ketenagakerjaan',
            'Gaji Pokok',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Karyawan';
    }
}
