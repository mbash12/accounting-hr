<?php

namespace App\Filament\Resources\OvertimeLogs\Pages;

use App\Filament\Resources\OvertimeLogs\OvertimeLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOvertimeLogs extends ListRecords
{
    protected static string $resource = OvertimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
