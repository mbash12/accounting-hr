<?php

namespace App\Filament\Resources\CashReceipts\Pages;

use App\Filament\Resources\CashReceipts\CashReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashReceipts extends ListRecords
{
    protected static string $resource = CashReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
