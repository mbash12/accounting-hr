<?php

namespace App\Filament\Actions;

use App\Imports\ProductImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Impor')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Produk')
                    ->helperText('Unggah file Excel (.xlsx) dengan data produk termasuk kolom: name, code, description, cost_price, selling_price, product_type, min_order_qty, unit_code, product_group_code, tax_code, supplier_code, dll. Pastikan kode grup produk, pajak, dan supplier yang digunakan sudah ada di sistem (diimpor terlebih dahulu melalui menu Grup Produk, Pajak, dan Kontak).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Impor Data Produk')
            ->modalDescription('Unggah file Excel dengan informasi produk. Pastikan untuk mengimpor Grup Produk terlebih dahulu sebelum mengimpor produk agar kategori dapat terhubung dengan benar. Anda dapat mengunduh template di bawah untuk melihat format yang diharapkan.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Unduh Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\ProductTemplateExport(),
                                'template-impor-produk.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Unduh Template Gagal')
                                ->body('Terjadi kesalahan saat mengunduh template produk: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new ProductImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body('Data produk berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Impor Gagal')
                        ->body('Terjadi kesalahan saat mengimpor data produk: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}