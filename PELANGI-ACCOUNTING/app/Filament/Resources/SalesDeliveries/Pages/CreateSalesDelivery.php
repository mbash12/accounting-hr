<?php

namespace App\Filament\Resources\SalesDeliveries\Pages;

use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateSalesDelivery extends CreateRecord
{
    protected static string $resource = SalesDeliveryResource::class;

    public function create(...$args): void
    {
        try {
            parent::create(...$args);
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

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return parent::handleRecordCreation($data);
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

    protected function afterCreate(): void
    {
        if ($this->record) {
            try {
                $this->record->createJournalEntry();
            } catch (\Exception $e) {
                \Log::error('Error creating journal entry for delivery document: ' . $e->getMessage(), [
                    'delivery_id' => $this->record->id,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
