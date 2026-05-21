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
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Product Data File')
                    ->helperText('Upload an Excel file (.xlsx) with product data including columns: name, code, description, cost_price, selling_price, product_type, unit_code, product_group_code, tax_code, etc. Make sure the product group and tax codes already exist in the system.')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Produk')
            ->modalDescription('Upload Excel file with produk. Pastikan untuk mengimpor Grup Produk terlebih dahulu sebelum mengimpor produk agar kategori dapat terhubung dengan benar. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\ProductTemplateExport(),
                                'product-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template produk: ' . $e->getMessage())
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
                        ->title('Import Successful')
                        ->body('Product data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing produk: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}