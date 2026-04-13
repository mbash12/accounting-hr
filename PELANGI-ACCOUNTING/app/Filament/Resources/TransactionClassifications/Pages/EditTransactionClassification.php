<?php

namespace App\Filament\Resources\TransactionClassifications\Pages;

use App\Filament\Resources\TransactionClassifications\TransactionClassificationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTransactionClassification extends EditRecord
{
    protected static string $resource = TransactionClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}



