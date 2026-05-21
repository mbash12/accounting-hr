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
            ->label('Import')
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
            ->modalHeading('Import Aturan Lembur')
            ->modalDescription('Upload Excel file with aturan lembur. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
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
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template aturan lembur: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new OvertimeRulesImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Data aturan lembur berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing aturan lembur: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
