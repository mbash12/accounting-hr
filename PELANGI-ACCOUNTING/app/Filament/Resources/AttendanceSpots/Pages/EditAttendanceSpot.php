<?php

namespace App\Filament\Resources\AttendanceSpots\Pages;

use App\Filament\Resources\AttendanceSpots\AttendanceSpotResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceSpot extends EditRecord
{
    protected static string $resource = AttendanceSpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
