<?php

namespace App\Filament\Resources\PayablePayments\Pages;

use App\Filament\Resources\PayablePayments\PayablePaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayablePayments extends ListRecords
{
    protected static string $resource = PayablePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}


