<?php

namespace App\Filament\Resources\AdvanceReceipts\Pages;

use App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdvanceReceipts extends ListRecords
{
    protected static string $resource = AdvanceReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
