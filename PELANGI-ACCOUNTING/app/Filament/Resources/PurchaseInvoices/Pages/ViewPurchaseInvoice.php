<?php

namespace App\Filament\Resources\PurchaseInvoices\Pages;

use App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseInvoice extends ViewRecord
{
    protected static string $resource = PurchaseInvoiceResource::class;

    protected string $view = 'filament.pages.purchase-invoice-view';

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
