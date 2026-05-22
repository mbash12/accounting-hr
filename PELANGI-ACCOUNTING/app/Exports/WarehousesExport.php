<?php

namespace App\Exports;

use App\Models\Warehouse;
use App\Services\CompanyFilterService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarehousesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $query = Warehouse::select([
            'code', 'name', 'is_active'
        ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Warehouse Code',
            'Warehouse Name',
            'Active Status',
        ];
    }

    public function map($warehouse): array
    {
        return [
            $warehouse->code,
            $warehouse->name,
            $warehouse->is_active ? 'Yes' : 'No',
        ];
    }
}
