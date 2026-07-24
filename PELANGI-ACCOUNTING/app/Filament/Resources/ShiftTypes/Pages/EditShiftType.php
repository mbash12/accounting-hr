<?php

namespace App\Filament\Resources\ShiftTypes\Pages;

use App\Filament\Resources\ShiftTypes\ShiftTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShiftType extends EditRecord
{
    protected static string $resource = ShiftTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
