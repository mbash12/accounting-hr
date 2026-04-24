<?php

namespace App\Filament\Resources\AttendanceSpots\Pages;

use App\Filament\Resources\AttendanceSpots\AttendanceSpotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceSpots extends ListRecords
{
    protected static string $resource = AttendanceSpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
