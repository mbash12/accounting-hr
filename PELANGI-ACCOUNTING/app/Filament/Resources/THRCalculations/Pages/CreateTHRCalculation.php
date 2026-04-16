<?php

namespace App\Filament\Resources\THRCalculations\Pages;

use App\Filament\Resources\THRCalculations\THRCalculationResource;
use App\Services\PayrollService;
use Filament\Resources\Pages\CreateRecord;

class CreateTHRCalculation extends CreateRecord
{
    protected static string $resource = THRCalculationResource::class;

    protected function afterCreate(): void
    {
        $service = app(PayrollService::class);
        $service->calculateTHRForPeriod($this->record);
    }
}
