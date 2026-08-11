<?php

namespace App\Filament\Resources\SalesDeliveries\Pages;

use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewDeliveryDocument extends ViewRecord
{
    protected static string $resource = SalesDeliveryResource::class;

    protected string $view = 'filament.pages.delivery-document-view';

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('printA4')
                    ->label('A4 - Portrait')
                    ->icon('heroicon-o-document')
                    ->url(url()->current() . '?print=1&paper=a4')
                    ->openUrlInNewTab(),
                Action::make('printA5')
                    ->label('A5 - Landscape')
                    ->icon('heroicon-o-document')
                    ->url(url()->current() . '?print=1&paper=a5')
                    ->openUrlInNewTab(),
            ])
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->button(),
        ];
    }
}
