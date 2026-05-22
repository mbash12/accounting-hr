<?php

namespace App\Filament\Resources\SalesReturns\Pages;

use App\Filament\Resources\SalesReturns\SalesReturnResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateSalesReturn extends CreateRecord
{
    protected static string $resource = SalesReturnResource::class;

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
