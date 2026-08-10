<?php

namespace App\Filament\Actions;

use App\Exports\AttendancesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ExportAttendancesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export PDF')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function (\Filament\Actions\Action $action) {
                try {
                    // Get active table filters from Livewire component
                    $filters = [];
                    $livewire = $action->getLivewire();
                    if ($livewire && property_exists($livewire, 'tableFilters')) {
                        $filters = $livewire->tableFilters ?? [];
                    }

                    $pdf = Pdf::loadView('filament.pages.attendance-export-pdf', [
                        'records' => (new AttendancesExport($filters))->collection(),
                        'generatedAt' => now(),
                    ])->setPaper('a4', 'landscape');

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'attendance-' . date('Y-m-d') . '.pdf');
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
