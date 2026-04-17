<?php

namespace App\Exports;

use App\Models\SalaryComponent;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalaryComponentsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = SalaryComponent::select(['code', 'name', 'type', 'is_fixed', 'is_taxable', 'is_bpjs_base', 'is_active']);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()->map(function ($component) {
            return [
                'code'         => $component->code,
                'name'         => $component->name,
                'type'         => $component->type,
                'is_fixed'     => $component->is_fixed ? 'yes' : 'no',
                'is_taxable'   => $component->is_taxable ? 'yes' : 'no',
                'is_bpjs_base' => $component->is_bpjs_base ? 'yes' : 'no',
                'active_status' => $component->is_active ? 'yes' : 'no',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Komponen',
            'Tipe',
            'Tetap',
            'Kena Pajak',
            'Basis BPJS',
            'Status Aktif',
        ];
    }

    public function title(): string
    {
        return 'Data Komponen Gaji';
    }
}
