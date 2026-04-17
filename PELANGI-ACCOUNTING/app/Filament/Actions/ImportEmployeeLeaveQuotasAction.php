<?php

namespace App\Filament\Actions;

use App\Imports\EmployeeLeaveQuotasImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployeeLeaveQuotasAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Kuota Cuti')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: employee_id, year, total_quota, used_quota')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Kuota Cuti')
            ->modalDescription('Unggah file Excel dengan data kuota cuti karyawan. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\EmployeeLeaveQuotasTemplateExport(),
                                'template-impor-kuota-cuti.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template kuota cuti: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new EmployeeLeaveQuotasImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data kuota cuti berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data kuota cuti: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
