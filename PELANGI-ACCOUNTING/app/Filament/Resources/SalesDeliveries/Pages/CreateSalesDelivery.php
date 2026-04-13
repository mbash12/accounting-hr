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
            $message = collect($e->errors())->flatten()->first() ?? __('Validasi gagal.');

            Notification::make()
                ->title(__('Gagal menyimpan'))
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
            $message = collect($e->errors())->flatten()->first() ?? __('Validasi gagal.');

            Notification::make()
                ->title(__('Gagal menyimpan'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
