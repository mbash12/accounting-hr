<?php

namespace App\Filament\Actions;

use App\Exports\PurchaseOrderWithItemsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportPurchaseOrderWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new PurchaseOrderWithItemsExport(),
                        'pesanan-pembelian-dan-item-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting pesanan pembelian dan item: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}