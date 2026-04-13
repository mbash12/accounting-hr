<?php

namespace App\Filament\Actions;

use App\Imports\SalesReturnWithItemsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportSalesReturnWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Retur Penjualan dan Item')
                    ->helperText('Unggah file Excel (.xlsx) dengan data retur penjualan dan itemnya. Setiap baris mewakili satu item dalam retur. Pastikan kode customer, produk, satuan yang digunakan sudah ada di sistem (diimpor terlebih dahulu melalui menu Kontak, Produk, dan Satuan).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Retur Penjualan dan Item')
            ->modalDescription('Unggah file Excel dengan informasi retur penjualan dan itemnya. Setiap baris dalam file mewakili satu item dalam retur. Retur dengan nomor yang sama akan digabungkan. Pastikan untuk mengimpor Customer, Produk, dan Satuan terlebih dahulu sebelum mengimpor retur penjualan agar data dapat terhubung dengan benar. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\SalesReturnWithItemsTemplateExport(),
                                'template-impor-retur-dan-item.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template retur dan item: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new SalesReturnWithItemsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data retur penjualan dan item berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data retur dan item: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}