<?php

namespace App\Filament\Actions;

use App\Imports\AccountsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportAccountsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Akun')
                    ->helperText('Upload file Excel (.xlsx) dengan data akun termasuk kolom: code, name, description, classification_type, is_header, is_cash_bank, is_active, level, parent_code')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Data Akun')
            ->modalDescription('Upload file Excel dengan informasi akun. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan. Kolom yang diharapkan: code, name, description, classification_type, is_header, is_cash_bank, is_active, level, parent_code.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\AccountsTemplateExport(),
                                'template-import-accounts.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Download Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template akun: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new AccountsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Berhasil')
                        ->body('Data akun berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data akun: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}