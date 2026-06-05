<?php

namespace App\Filament\Resources\JournalEntries\Pages;

use App\Filament\Actions\BulkInputJournalItemsAction;
use App\Filament\Resources\JournalEntries\JournalEntryResource;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryForm;
use App\Filament\Forms\Components\NumberInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;


class CreateJournalEntry extends CreateRecord
{
    protected static string $resource = JournalEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BulkInputJournalItemsAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        $isBalanced = function () {
            $items = $this->data['items'] ?? [];
            $totals = JournalEntryForm::calculateTotalsFromItems($items);
            return abs((float)$totals['balance']) < 0.01 && $totals['total_debit'] > 0;
        };

        return [
            Action::make('save')
                ->label(__('Save'))
                ->action('create')
                ->keyBindings(['mod+s'])
                ->visible($isBalanced),
        ];
    }

    protected bool $saveAsDraft = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $this->data['items'] ?? [];
        
        $validItems = array_filter($items, function ($item) {
            $item = (array) $item;
            $hasAccount = !empty($item['account_id'] ?? null);
            $debit = (float) ($item['debit'] ?? 0);
            $credit = (float) ($item['credit'] ?? 0);
            return $hasAccount && ($debit > 0 || $credit > 0);
        });
        
        $validItems = array_values($validItems);
        
        if (count($validItems) < 2) {
            $this->NotificationHalt('Journal must have at least 2 items with account and debit/credit values.');
        }
        
        $totals = JournalEntryForm::calculateTotalsFromItems($validItems);

        if (abs((float)$totals['balance']) >= 0.01) {
            $this->NotificationHalt('Total debit and credit must be balanced.');
        }

        foreach ($validItems as $index => $item) {
            $item = (array) $item;
            $debit = (float) ($item['debit'] ?? 0);
            $credit = (float) ($item['credit'] ?? 0);
            
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
        
        $data['is_posted'] = true;
        $data['status'] = 'posted';
        $data['posted_by_user_id'] = Filament::auth()->id();
        $data['posted_at'] = now();
        $data['created_by_user_id'] = Filament::auth()->id();
        
        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
