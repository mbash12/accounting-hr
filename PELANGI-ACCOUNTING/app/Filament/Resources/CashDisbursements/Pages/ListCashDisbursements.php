<?php

namespace App\Filament\Resources\CashDisbursements\Pages;

use App\Filament\Resources\CashDisbursements\CashDisbursementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashDisbursements extends ListRecords
{
    protected static string $resource = CashDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
