<?php

namespace App\Filament\Actions;

use App\Exports\DepartmentsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportDepartmentsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    return Excel::download(
                        new DepartmentsExport(),
                        'departments-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting department data: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
