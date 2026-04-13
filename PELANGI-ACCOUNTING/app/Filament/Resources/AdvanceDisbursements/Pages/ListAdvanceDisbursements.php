<?php

namespace App\Filament\Resources\AdvanceDisbursements\Pages;

use App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdvanceDisbursements extends ListRecords
{
    protected static string $resource = AdvanceDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
