<?php

namespace App\Filament\Actions;

use App\Imports\PaymentTermImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportPaymentTermsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Payment Term Data File')
                    ->helperText('Upload Excel file (.xlsx) with payment term data including columns: kode_termin, nama_termin, jumlah_hari, status_aktif, deskripsi. If kode_termin is empty, it will be auto-generated.')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024) // 1MB
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Termin Pembayaran')
            ->modalDescription('Upload Excel file with termin pembayaran. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\PaymentTermTemplateExport(),
                                'payment-term-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template termin pembayaran: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    Excel::import(new PaymentTermImport, $filePath);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Payment term data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing termin pembayaran: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
