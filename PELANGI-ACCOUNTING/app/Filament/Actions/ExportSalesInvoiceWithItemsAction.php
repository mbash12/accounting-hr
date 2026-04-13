<?php

namespace App\Filament\Actions;

use App\Exports\SalesInvoiceWithItemsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportSalesInvoiceWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Ekspor')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new SalesInvoiceWithItemsExport(),
                        'faktur-dan-item-penjualan-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Ekspor Gagal')
                        ->body('Terjadi kesalahan saat mengekspor data faktur dan item penjualan: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}