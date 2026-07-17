<?php

namespace App\Filament\Resources\JournalEntries\Pages;

use App\Filament\Actions\BulkInputJournalItemsAction;
use App\Filament\Resources\JournalEntries\JournalEntryResource;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryForm;
use App\Filament\Forms\Components\NumberInput;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\TextInput;

class EditJournalEntry extends EditRecord
{
    public ?string $reason = null;

    protected static string $resource = JournalEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BulkInputJournalItemsAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save'))
                ->action(function (array $data) {
                    $this->reason = $data['reason'] ?? null;
                    $this->save();
                })
                ->modalHeading(__('Change Reason'))
                ->modalWidth('md')
                ->form([
                    TextInput::make('reason')
                        ->label(__('Reason'))
                        ->required()
                        ->placeholder(__('Reason must be at least 5 characters'))
                        ->minLength(5),
                ])
                ->modalSubmitActionLabel(__('Submit'))
                ->modalCancelActionLabel(__('Cancel'))
                ->keyBindings(['mod+s'])
                ->visible(function () {
                    $items = $this->data['items'] ?? [];
                    $totals = JournalEntryForm::calculateTotalsFromItems($items);
                    return abs((float)$totals['balance']) < 0.01 && $totals['total_debit'] > 0;
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = $this->data['items'] ?? [];
        
        // Filter out empty items
        $validItems = array_filter($items, function ($item) {
            $item = (array) $item;
            $hasAccount = !empty($item['account_id'] ?? null);
            $debit = (float) ($item['debit'] ?? 0);
            $credit = (float) ($item['credit'] ?? 0);
            return $hasAccount && ($debit > 0 || $credit > 0);
        });
        
        $validItems = array_values($validItems);
        
        if (count($validItems) < 2) {
            $this->NotificationHalt('Journal must have at least 2 items with account and debit/credit values. Current valid items: ' . count($validItems));
        }

        $totals = JournalEntryForm::calculateTotalsFromItems($validItems);
        $companyId = $data['company_id'] ?? $this->record->company_id ?? null;

        foreach ($validItems as $index => $item) {
            $item = (array) $item;
            $debit = (float) ($item['debit'] ?? 0);
            $credit = (float) ($item['credit'] ?? 0);

            if (!empty($companyId)) {
                $accountBelongsToCompany = \App\Models\Account::where('id', $item['account_id'])
                    ->where('company_id', $companyId)
                    ->exists();

                if (!$accountBelongsToCompany) {
                    $this->NotificationHalt('Item ' . ($index + 1) . ' account does not belong to the selected company.');
                }
            }
            
            if ($debit <= 0 && $credit <= 0) {
                $this->NotificationHalt('Item ' . ($index + 1) . ' must have a debit or credit value.');
            }
            
            if ($debit > 0 && $credit > 0) {
                $this->NotificationHalt('Item ' . ($index + 1) . ' cannot have both debit and credit values.');
            }
        }

        $data['items'] = $validItems;
        $data['amount'] = $totals['total_debit'];
        $data['total_amount'] = $totals['total_debit'];

        $date = $data['date'] ?? $this->record->date ?? null;
        if (!empty($companyId) && !empty($date)) {
            try {
                app(\App\Services\PeriodClosingService::class)->assertOpen((int) $companyId, $date);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->NotificationHalt(collect($e->errors())->flatten()->first() ?? 'Period is closed.');
            }
        }
        
        // Preserve existing posted status - use Posting Center to post
        unset($data['is_posted']);

        $data['updated_by_user_id'] = \Illuminate\Support\Facades\Auth::id();

        return $data;
    }

    protected function NotificationHalt(string $message): void
    {
        \Filament\Notifications\Notification::make()
            ->danger()
            ->title($message)
            ->send();
        
        $this->halt();
    }
}
