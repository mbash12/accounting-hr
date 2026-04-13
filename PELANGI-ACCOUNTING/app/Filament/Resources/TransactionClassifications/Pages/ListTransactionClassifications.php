<?php

namespace App\Filament\Resources\TransactionClassifications\Pages;

use App\Filament\Resources\TransactionClassifications\TransactionClassificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransactionClassifications extends ListRecords
{
    protected static string $resource = TransactionClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



