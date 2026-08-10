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
                    // Keep PDF exports working on deployments that have not yet
                    // loaded the application's PHP configuration file.
                    ini_set('memory_limit', '512M');

                    // Reuse Filament's export query so the PDF contains exactly
                    // the records matching the active table filters/search.
                    $filters = [];
                    $livewire = $action->getLivewire();
                    if ($livewire && property_exists($livewire, 'tableFilters')) {
                        $filters = $livewire->tableFilters ?? [];
                    }

                    $export = ($livewire && method_exists($livewire, 'getTableQueryForExport'))
                        ? new AttendancesExport(query: $livewire->getTableQueryForExport())
                        : new AttendancesExport($filters);

                    $pdf = Pdf::loadView('filament.pages.attendance-export-pdf', [
                        'records' => $export->collection(),
                        'generatedAt' => now(),
                    ])->setPaper('a4', 'landscape')
                        ->setOption('isHtml5ParserEnabled', false);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'attendance-' . date('Y-m-d') . '.pdf');
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export Failed')
                        ->body('An error occurred while exporting attendance: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
