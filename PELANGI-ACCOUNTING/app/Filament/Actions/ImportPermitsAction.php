<?php

namespace App\Filament\Actions;

use App\Imports\PermitsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportPermitsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Izin & Cuti')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: employee_id, type, start_date, end_date, reason, status (pending/approved/rejected)')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Izin & Cuti')
            ->modalDescription('Upload Excel file with data izin dan cuti karyawan. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\PermitsTemplateExport(),
                                'template-impor-izin-cuti.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template izin & cuti: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new PermitsImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Data izin & cuti berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing izin & cuti: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
