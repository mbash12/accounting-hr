<?php

namespace App\Filament\Actions;

use App\Imports\ContactsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportContactsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Kontak')
                    ->helperText('Unggah file Excel (.xlsx) dengan data kontak. Gunakan template impor untuk melihat format kolom yang benar: name, email, phone, contact_code, contact_person, is_customer, is_supplier, is_employee, is_active, billing_address_line_1, delivery_address_line_1, tax, is_pkp')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Kontak')
            ->modalDescription('Unggah file Excel dengan informasi kontak. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\ContactsTemplateExport(),
                                'template-impor-kontak.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template kontak: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new ContactsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data kontak berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data kontak: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}