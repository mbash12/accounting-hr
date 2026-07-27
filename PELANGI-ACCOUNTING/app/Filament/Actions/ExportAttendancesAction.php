<?php

namespace App\Filament\Actions;

use App\Exports\AttendancesExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportAttendancesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                try {
                    // Get active table filters from Livewire component
                    $filters = [];
                    $livewire = $this->getLivewire();
                    if ($livewire && property_exists($livewire, 'tableFilters')) {
                        $filters = $livewire->tableFilters ?? [];
                    }

                    return Excel::download(
                        new AttendancesExport($filters),
                        'attendance-' . date('Y-m-d') . '.xlsx'
                    );
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting attendance: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
