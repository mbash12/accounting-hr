<?php

namespace App\Filament\Actions;

use App\Imports\HolidaysImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportHolidaysAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Holiday Data File')
                    ->helperText('Upload Excel file (.xlsx) with columns: name, date, is_cuti_bersama')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Holidays')
            ->modalDescription('Upload Excel file with holiday data. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\HolidaysTemplateExport(),
                                'holiday-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading holiday template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new HolidaysImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Holiday data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing holidays: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
