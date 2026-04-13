<?php

namespace App\Filament\Resources\ReceivablePayments\Pages;

use App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceivablePayments extends ListRecords
{
    protected static string $resource = ReceivablePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}











