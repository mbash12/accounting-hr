<?php

namespace App\Filament\Resources\THRCalculations\Pages;

use App\Filament\Resources\THRCalculations\THRCalculationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTHRCalculations extends ListRecords
{
    protected static string $resource = THRCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
