<?php

namespace App\Exports;

use App\Models\Expedition;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExpeditionExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = Expedition::select([
                'name', 'code', 'is_active'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()
            ->map(function ($expedition) {
                return [
                    'code' => $expedition->code,
                    'name' => $expedition->name,
                    'is_active' => $expedition->is_active ? 'ya' : 'tidak',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Ekspedisi',
            'Nama Ekspedisi',
            'Status Aktif'
        ];
    }

    public function title(): string
    {
        return 'Data Ekspedisi';
    }
}