<?php

namespace App\Filament\Resources\BonusCalculations\Pages;

use App\Filament\Resources\BonusCalculations\BonusCalculationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBonusCalculations extends ListRecords
{
    protected static string $resource = BonusCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
