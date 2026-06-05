<?php

namespace App\Filament\Actions;

use App\Exports\JournalEntryTemplateExport;
use App\Imports\JournalEntryImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportJournalEntriesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Journal Entry File')
                    ->helperText('Upload an Excel file (.xlsx) with journal entry data. Each row represents one line item. Rows with the same No Entry will be grouped into one journal entry. You can download the template below to see the expected format.')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(1024)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Journal Entries')
            ->modalDescription('Upload an Excel file with journal entry data. Each row represents one line item. Rows with the same No Entry will be grouped into one journal entry. Make sure account codes already exist in the system. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new JournalEntryTemplateExport(),
                                'journal-entry-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading the template: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    $companyId = session('selected_company_id');
                    $userId = auth()->id();

                    Excel::import(
                        new JournalEntryImport($companyId, $userId),
                        $filePath
                    );

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Journal entries imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing journal entries: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
