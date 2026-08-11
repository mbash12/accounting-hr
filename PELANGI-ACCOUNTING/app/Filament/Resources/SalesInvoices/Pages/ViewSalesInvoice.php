<?php

namespace App\Filament\Resources\SalesInvoices\Pages;

use App\Filament\Resources\SalesInvoices\SalesInvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesInvoice extends ViewRecord
{
    protected static string $resource = SalesInvoiceResource::class;

    protected string $view = 'filament.pages.sales-invoice-view';

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
