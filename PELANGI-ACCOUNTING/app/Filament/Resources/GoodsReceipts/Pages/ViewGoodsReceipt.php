<?php

namespace App\Filament\Resources\GoodsReceipts\Pages;

use App\Filament\Actions\CreatePurchaseInvoiceFromGoodsReceipt;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewGoodsReceipt extends ViewRecord
{
    protected static string $resource = GoodsReceiptResource::class;

    protected string $view = 'filament.pages.goods-receipt-view';

    protected function getHeaderActions(): array
    {
        return [
            CreatePurchaseInvoiceFromGoodsReceipt::make(),
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(url()->current() . '?print=1')
                ->openUrlInNewTab(),
        ];
    }
}
