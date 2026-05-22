<?php

namespace App\Filament\Actions;

use App\Imports\PurchaseInvoiceWithItemsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportPurchaseInvoiceWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Purchase Invoice and Items File')
                    ->helperText('Upload an Excel file (.xlsx) with purchase invoice and item data. Each row represents one item in an invoice. Make sure supplier, product, unit, and tax codes already exist in the system (import them first via Contacts, Products, Units, and Tax menus).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Purchase Invoice and Items Data')
            ->modalDescription('Upload an Excel file with purchase invoice and item information. Each row represents one item in an invoice. Invoices with the same number will be merged. Make sure to import Suppliers, Products, Units, and Taxes first before importing purchase invoices so data can be linked correctly. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\PurchaseInvoiceWithItemsTemplateExport(),
                                'purchase-invoice-items-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading the purchase invoice and items template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new PurchaseInvoiceWithItemsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Purchase invoice and items data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing purchase invoice and items data: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
