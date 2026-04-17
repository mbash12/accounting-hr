<?php

namespace App\Exports;

use App\Models\Holiday;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class HolidaysExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Holiday::select(['name', 'date', 'is_cuti_bersama']);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()->map(function ($holiday) {
            return [
                'name'           => $holiday->name,
                'date'           => $holiday->date?->format('Y-m-d'),
                'is_cuti_bersama' => $holiday->is_cuti_bersama ? 'yes' : 'no',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Hari Libur',
            'Tanggal',
            'Cuti Bersama',
        ];
    }

    public function title(): string
    {
        return 'Data Hari Libur';
    }
}
