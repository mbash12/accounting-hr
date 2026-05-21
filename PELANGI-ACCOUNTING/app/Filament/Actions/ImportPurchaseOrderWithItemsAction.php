<?php

namespace App\Filament\Actions;

use App\Imports\PurchaseOrderWithItemsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportPurchaseOrderWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Purchase Order and Items File')
                    ->helperText('Upload an Excel file (.xlsx) with purchase order and item data. Each row represents one item in an order. Make sure supplier, product, unit, and tax codes already exist in the system (import them first via Contacts, Products, Units, and Tax menus).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Purchase Order and Items Data')
            ->modalDescription('Upload an Excel file with purchase order and item information. Each row represents one item in an order. Orders with the same number will be merged. Make sure to import Suppliers, Products, Units, and Taxes first before importing purchase orders so data can be linked correctly. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\PurchaseOrderWithItemsTemplateExport(),
                                'purchase-order-items-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading the purchase order and items template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new PurchaseOrderWithItemsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Purchase order and items data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing purchase order and items data: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
