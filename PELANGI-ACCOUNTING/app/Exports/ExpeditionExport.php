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
                    'is_active' => $expedition->is_active ? 'Yes' : 'No',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Expedition Code',
            'Expedition Name',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Expedition Data';
    }
}
