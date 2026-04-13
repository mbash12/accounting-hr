<?php

namespace App\Filament\Actions;

use App\Exports\AccountsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportAccountsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new AccountsExport(),
                        'accounts-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Gagal')
                        ->body('Terjadi kesalahan saat mengekspor data akun: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}