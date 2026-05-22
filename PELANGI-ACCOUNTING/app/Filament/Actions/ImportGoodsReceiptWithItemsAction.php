<?php

namespace App\Filament\Actions;

use App\Imports\GoodsReceiptWithItemsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportGoodsReceiptWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Goods Receipt and Items File')
                    ->helperText('Upload an Excel file (.xlsx) with goods receipt and item data. Each row represents one item in a receipt. Make sure supplier, product, and unit codes already exist in the system (import them first via Contacts, Products, and Units menus).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Goods Receipt and Items Data')
            ->modalDescription('Upload an Excel file with goods receipt and item information. Each row represents one item in a receipt. Receipts with the same number will be merged. Make sure to import Suppliers, Products, and Units first before importing goods receipts so data can be linked correctly. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\GoodsReceiptWithItemsTemplateExport(),
                                'goods-receipt-items-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading the goods receipt and items template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new GoodsReceiptWithItemsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Goods receipt and items data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing goods receipt and items data: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
