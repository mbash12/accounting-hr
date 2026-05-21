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
                    ->label('File Data Hari Libur')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: name, date, is_cuti_bersama')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Hari Libur')
            ->modalDescription('Upload Excel file with hari libur. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\HolidaysTemplateExport(),
                                'template-impor-hari-libur.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template hari libur: ' . $e->getMessage())
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
                        ->body('Data hari libur berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing hari libur: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
