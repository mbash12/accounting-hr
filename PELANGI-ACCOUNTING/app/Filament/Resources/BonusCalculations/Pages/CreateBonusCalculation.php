<?php

namespace App\Filament\Resources\BonusCalculations\Pages;

use App\Filament\Resources\BonusCalculations\BonusCalculationResource;
use App\Services\PayrollService;
use Filament\Resources\Pages\CreateRecord;

class CreateBonusCalculation extends CreateRecord
{
    protected static string $resource = BonusCalculationResource::class;

    protected function afterCreate(): void
    {
        $service = app(PayrollService::class);
        $service->calculateBonusForPeriod($this->record);
    }
}
