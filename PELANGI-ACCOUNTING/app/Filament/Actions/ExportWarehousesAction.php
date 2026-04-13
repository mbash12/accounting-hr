<?php

namespace App\Filament\Actions;

use App\Exports\WarehousesExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportWarehousesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Ekspor')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new WarehousesExport(),
                        'gudang-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Ekspor Gagal')
                        ->body('Terjadi kesalahan saat mengekspor data gudang: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}