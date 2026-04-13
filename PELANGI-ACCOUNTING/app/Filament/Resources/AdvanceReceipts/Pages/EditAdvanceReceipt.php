<?php

namespace App\Filament\Resources\AdvanceReceipts\Pages;

use App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvanceReceipt extends EditRecord
{
    protected static string $resource = AdvanceReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by_user_id'] = auth()->id();

        return $data;
    }
}
