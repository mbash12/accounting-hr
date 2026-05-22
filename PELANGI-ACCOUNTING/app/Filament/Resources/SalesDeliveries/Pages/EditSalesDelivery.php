<?php

namespace App\Filament\Resources\SalesDeliveries\Pages;

use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditSalesDelivery extends EditRecord
{
    protected static string $resource = SalesDeliveryResource::class;

    public function save(...$args): void
    {
        try {
            parent::save(...$args);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validation failed.');

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return parent::handleRecordUpdate($record, $data);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validation failed.');

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
