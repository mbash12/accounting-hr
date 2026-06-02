<?php

namespace App\Filament\Actions;

use App\Services\JournalEntryBulkInputService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class BulkInputJournalItemsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'bulkInputJournalItems')
            ->label(__('Bulk Input from Excel'))
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label(__('Excel File'))
                    ->helperText(__('Upload an Excel file (.xlsx) with columns: No COA, Deskripsi, Debit, Credit. Download the template for the expected format.'))
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(2048)
                    ->required()
                    ->disk('local')
                    ->directory('temp/bulk-input'),
            ])
            ->modalHeading(__('Bulk Input Journal Items'))
            ->modalDescription(__('Upload an Excel file to pre-fill the journal items. Existing items will be preserved — new items are appended.'))
            ->modalSubmitActionLabel(__('Upload & Fill'))
            ->modalWidth('lg')
            ->extraModalActions([
                Action::make('download_template')
                    ->label(__('Download Template'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return \Maatwebsite\Excel\Facades\Excel::download(
                                new \App\Exports\JournalEntryBulkInputTemplateExport(),
                                'template-jurnal-umum.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('Template Download Failed'))
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data, $livewire) {

                try {
                    $filePath = Storage::disk('local')->path($data['file']);

                    $companyId = $livewire->data['company_id'] ?? null;
                    if (! $companyId) {
                        $selectedCompanyId = session('selected_company_id');
                        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                            $companyId = (int) $selectedCompanyId;
                        }
                    }

                    $service = app(JournalEntryBulkInputService::class);
                    $newItems = $service->parse($filePath, $companyId);
                    // Get existing items and append new ones, preserving existing keys
                    $existingItems = $livewire->data['items'] ?? [];

                    // If all existing items are empty placeholders, replace them entirely
                    $allEmpty = empty($existingItems)
                        || collect($existingItems)->every(fn($item) => empty(($item['account_id'] ?? null)) && (float)($item['debit'] ?? 0) == 0 && (float)($item['credit'] ?? 0) == 0);

                    $merged = $allEmpty ? $newItems : array_merge(array_values($existingItems), $newItems);

                    // Update Livewire component data
                    $livewire->data['items'] = $merged;
                    $livewire->form->fill($livewire->data);
                    // Clean up temp file
                    Storage::disk('local')->delete($data['file']);

                    Notification::make()
                        ->success()
                        ->title(__('Bulk Input Successful'))
                        ->body(__(':count item(s) imported.', ['count' => count($newItems)]))
                        ->send();
                } catch (\Exception $e) {
                    // Clean up temp file on error too
                    if (isset($data['file'])) {
                        Storage::disk('local')->delete($data['file']);
                    }

                    Notification::make()
                        ->danger()
                        ->title(__('Bulk Input Failed'))
                        ->body($e->getMessage())
                        ->send();
                }
            });
    }
}
