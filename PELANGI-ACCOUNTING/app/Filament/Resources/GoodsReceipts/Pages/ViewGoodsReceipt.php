<?php

namespace App\Filament\Resources\GoodsReceipts\Pages;

use App\Filament\Actions\CreatePurchaseInvoiceFromGoodsReceipt;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewGoodsReceipt extends ViewRecord
{
    protected static string $resource = GoodsReceiptResource::class;

    protected string $view = 'filament.pages.goods-receipt-view';

    protected function getHeaderActions(): array
    {
        return [
            CreatePurchaseInvoiceFromGoodsReceipt::make(),
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
