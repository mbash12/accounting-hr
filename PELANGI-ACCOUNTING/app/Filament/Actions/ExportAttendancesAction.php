<?php

namespace App\Filament\Actions;

use App\Exports\AttendancesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class ExportAttendancesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'export')
            ->label('Export PDF')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function (Action $action, Component $livewire) {
                try {
                    // Keep PDF exports working on deployments that have not yet
                    // loaded the application's PHP configuration file.
                    ini_set('memory_limit', '512M');

                    $tableLivewire = $action->getTable()?->getLivewire() ?? $livewire;

                    if (! $tableLivewire instanceof HasTable) {
                        throw new \RuntimeException('Attendance export must be run from the attendance table.');
                    }

                    $filters = [];
                    foreach (['date_range', 'employee_id', 'department_id', 'clock_source'] as $filterName) {
                        $filters[$filterName] = $tableLivewire->getTableFilterState($filterName) ?? [];
                    }

                    $expectedRecordCount = $tableLivewire->getAllTableRecordsCount();
                    $records = (new AttendancesExport(
                        filters: $filters,
                        query: $tableLivewire->getTableQueryForExport(),
                    ))->collection();

                    // The fallback uses the same resource scope as the table.
                    // Never download an unfiltered file if the two counts differ.
                    if ($records->count() !== $expectedRecordCount) {
                        $records = (new AttendancesExport($filters))->collection();
                    }

                    if ($records->count() !== $expectedRecordCount) {
                        throw new \RuntimeException('The attendance export does not match the active table filters. Please refresh the table and try again.');
                    }

                    $pdf = Pdf::loadView('filament.pages.attendance-export-pdf', [
                        'records' => $records,
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
