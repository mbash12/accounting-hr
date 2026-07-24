<?php

namespace App\Filament\Actions;

use App\Exports\ShiftScheduleTemplateExport;
use App\Imports\ShiftScheduleImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportShiftScheduleAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'upload')
            ->label('Upload Schedule')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('primary')
            ->form([
                FileUpload::make('file')
                    ->label('Excel file')
                    ->helperText('Upload .xlsx — either long format (employee_id, date, shift_code) or wide format (employee_id, name, department, 1, 2, …, 31)')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ])
                    ->maxSize(2048)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Upload Shift Schedule')
            ->modalDescription('Upload an Excel file. Use the "Download Template" button to get the right format. The current month/year is taken from the board selector.')
            ->modalSubmitActionLabel('Import')
            ->extraModalActions([
                Action::make('downloadEmpty')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function ($livewire) {
                        try {
                            $year  = (int) $livewire->year;
                            $month = (int) $livewire->month;
                            $dept  = null;
                            $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
                                ? (int) session('selected_company_id') : null;

                            return Excel::download(
                                new ShiftScheduleTemplateExport(
                                    year:         $year,
                                    month:        $month,
                                    departmentId: $dept ? (int) $dept : null,
                                    companyId:    $companyId,
                                    prefill:      false,
                                ),
                                "shift-template-{$year}-" . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx'
                            );
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template download failed')
                                ->body($e->getMessage())
                                ->send();
                            return null;
                        }
                    }),
            ])
            ->action(function (array $data, $livewire) {
                try {
                    $year  = (int) $livewire->year;
                    $month = (int) $livewire->month;
                    $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
                        ? (int) session('selected_company_id') : null;

                    $import = new ShiftScheduleImport($year, $month, $companyId);
                    Excel::import($import, $data['file']);

                    $body = "Inserted: {$import->inserted} • Updated: {$import->updated} • Skipped: {$import->skipped}";
                    if (! empty($import->errors)) {
                        $body .= "\n\nFirst errors:\n" . implode("\n", array_slice($import->errors, 0, 5));
                    }

                    Notification::make()
                        ->title('Import complete')
                        ->body($body)
                        ->success()
                        ->send();

                    // Trigger refresh board via event — #[On('shift-schedule-uploaded')] akan
                    // otomatis menjalankan refreshGrid() di page, tanpa redirect
                    $livewire->dispatch('shift-schedule-uploaded');
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import failed')
                        ->body($e->getMessage())
                        ->send();
                    return null;
                }
            });
    }
}
