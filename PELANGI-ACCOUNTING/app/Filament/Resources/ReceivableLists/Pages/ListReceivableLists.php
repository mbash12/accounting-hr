<?php

namespace App\Filament\Resources\ReceivableLists\Pages;

use App\Filament\Resources\ReceivableLists\ReceivableListResource;
use Filament\Resources\Pages\ListRecords;

class ListReceivableLists extends ListRecords
{
    protected static string $resource = ReceivableListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - this is a read-only list
        ];
    }
}











