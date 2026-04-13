<?php

namespace App\Filament\Actions;

use App\Imports\ProductGroupsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductGroupsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Grup Produk')
                    ->helperText('Unggah file Excel (.xlsx) dengan data grup produk termasuk kolom: nama_grup_produk, kode_grup_produk, tipe_pengiriman, status_aktif. Kode grup produk akan digunakan saat mengimpor produk.')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Grup Produk')
            ->modalDescription('Unggah file Excel dengan informasi grup produk. Kode grup produk yang diimpor akan digunakan saat mengimpor produk untuk menghubungkan produk ke kategori yang sesuai. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\ProductGroupsTemplateExport(),
                                'template-impor-grup-produk.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template grup produk: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new ProductGroupsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data grup produk berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data grup produk: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}