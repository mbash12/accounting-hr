<?php

namespace App\Filament\Resources\SalesDeliveries\Pages;

use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewDeliveryDocument extends ViewRecord
{
    protected static string $resource = SalesDeliveryResource::class;

    protected string $view = 'filament.pages.delivery-document-view';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(url()->current() . '?print=1')
                ->openUrlInNewTab(),
        ];
    }
}
