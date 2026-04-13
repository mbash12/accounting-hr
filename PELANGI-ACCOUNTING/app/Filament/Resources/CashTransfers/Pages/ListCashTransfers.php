<?php

namespace App\Filament\Resources\CashTransfers\Pages;

use App\Filament\Resources\CashTransfers\CashTransferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashTransfers extends ListRecords
{
    protected static string $resource = CashTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
