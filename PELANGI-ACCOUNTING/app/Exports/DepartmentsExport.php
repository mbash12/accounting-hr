<?php

namespace App\Exports;

use App\Models\Department;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Department::select(['code', 'name', 'is_active']);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($department) {
                return [
                    'department_code' => $department->code,
                    'department_name' => $department->name,
                    'active_status' => $department->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Departemen',
            'Nama Departemen',
            'Status Aktif'
        ];
    }

    public function title(): string
    {
        return 'Data Departemen';
    }
}