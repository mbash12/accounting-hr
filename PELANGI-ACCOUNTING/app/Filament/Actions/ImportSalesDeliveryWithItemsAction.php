<?php

namespace App\Filament\Actions;

use App\Imports\SalesDeliveryWithItemsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportSalesDeliveryWithItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Sales Delivery and Items File')
                    ->helperText('Upload an Excel file (.xlsx) with sales delivery and item data. Each row represents one item in a delivery. Make sure customer, product, and unit codes already exist in the system (import them first via Contacts, Products, and Units menus).')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Sales Delivery and Items Data')
            ->modalDescription('Upload an Excel file with sales delivery and item information. Each row represents one item in a delivery. Deliveries with the same number will be merged. Make sure to import Customers, Products, and Units first before importing sales deliveries so data can be linked correctly. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\SalesDeliveryWithItemsTemplateExport(),
                                'sales-delivery-items-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading the sales delivery and items template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new SalesDeliveryWithItemsImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Sales delivery and items data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing sales delivery and items data: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
