<?php

namespace App\Filament\Actions;

use App\Imports\OvertimeRulesImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportOvertimeRulesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Aturan Lembur')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: name, department_code, is_default, base_hourly_rate_divisor, workday_first_hour_multiplier, workday_subsequent_hour_multiplier, holiday_multiplier, active_status')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Aturan Lembur')
            ->modalDescription('Unggah file Excel dengan informasi aturan lembur. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\OvertimeRulesTemplateExport(),
                                'template-impor-aturan-lembur.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template aturan lembur: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new OvertimeRulesImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data aturan lembur berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data aturan lembur: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
