<?php

namespace App\Filament\Actions;

use App\Exports\SalesOrderWithItemsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportSalesOrderWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new SalesOrderWithItemsExport(),
                        'pesanan-dan-item-penjualan-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting pesanan dan item penjualan: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}