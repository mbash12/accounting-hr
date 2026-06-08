<?php

namespace App\Filament\Resources\UnitMeasurements\Pages;

use App\Filament\Resources\UnitMeasurements\UnitMeasurementResource;
use Filament\Resources\Pages\EditRecord;

class EditUnitMeasurement extends EditRecord
{
    protected static string $resource = UnitMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
