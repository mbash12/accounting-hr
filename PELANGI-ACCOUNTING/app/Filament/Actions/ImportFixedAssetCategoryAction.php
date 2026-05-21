<?php

namespace App\Filament\Actions;

use App\Imports\FixedAssetCategoryImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportFixedAssetCategoryAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Fixed Asset Category Data File')
                    ->helperText('Upload Excel file (.xlsx) with fixed asset category data including columns: name, code, depreciation_method, useful_life, is_active, sales_account_code, asset_account_code, accumulated_depreciation_account_code, depreciation_account_code')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Kategori Aset Tetap')
            ->modalDescription('Upload Excel file with kategori aset tetap. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\FixedAssetCategoryTemplateExport(),
                                'fixed-asset-category-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template kategori aset tetap: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new FixedAssetCategoryImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Fixed asset category data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing kategori aset tetap: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}