<?php

namespace App\Exports;

use App\Models\Unit;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UnitMeasurementsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Unit::select(['code', 'name', 'description', 'is_active']);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($unit) {
                return [
                    'unit_code' => $unit->code,
                    'unit_name' => $unit->name,
                    'unit_description' => $unit->description,
                    'active_status' => $unit->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Satuan',
            'Nama Satuan',
            'Deskripsi Satuan',
            'Status Aktif'
        ];
    }

    public function title(): string
    {
        return 'Data Satuan';
    }
}