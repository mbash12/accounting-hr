<?php

namespace App\Filament\Resources\AdvanceDisbursements\Pages;

use App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAdvanceDisbursement extends EditRecord
{
    protected static string $resource = AdvanceDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
