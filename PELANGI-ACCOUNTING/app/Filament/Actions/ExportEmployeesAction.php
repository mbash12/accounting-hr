<?php

namespace App\Filament\Actions;

use App\Exports\EmployeesExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportEmployeesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function (\Filament\Actions\Action $action) {
                try {
                    // Export the same records currently shown in the table.
                    $filters = [];
                    $livewire = $action->getLivewire();
                    if ($livewire && property_exists($livewire, 'tableFilters')) {
                        $filters = $livewire->tableFilters ?? [];
                    }

                    return Excel::download(
                        new EmployeesExport($filters),
                        'employees-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting employees: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
