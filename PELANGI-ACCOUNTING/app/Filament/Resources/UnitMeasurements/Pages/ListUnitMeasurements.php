<?php

namespace App\Filament\Resources\UnitMeasurements\Pages;

use App\Filament\Resources\UnitMeasurements\UnitMeasurementResource;
use Filament\Resources\Pages\ListRecords;

class ListUnitMeasurements extends ListRecords
{
    protected static string $resource = UnitMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
