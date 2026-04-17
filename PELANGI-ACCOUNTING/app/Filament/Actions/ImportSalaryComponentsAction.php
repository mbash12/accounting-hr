<?php

namespace App\Filament\Actions;

use App\Imports\SalaryComponentsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportSalaryComponentsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Komponen Gaji')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: code, name, type (allowance/deduction), is_fixed, is_taxable, is_bpjs_base, active_status')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Komponen Gaji')
            ->modalDescription('Unggah file Excel dengan informasi komponen gaji. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\SalaryComponentsTemplateExport(),
                                'template-impor-komponen-gaji.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template komponen gaji: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new SalaryComponentsImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data komponen gaji berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data komponen gaji: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
