<?php

namespace App\Filament\Resources\JournalEntries\Pages;

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
            $itemArray = (array) $item;
            
            if (empty($itemArray['account_id'])) {
                return false;
            }
            
            $debit = NumberInput::parseToFloat($itemArray['debit'] ?? $itemArray['debit_display'] ?? 0);
            $credit = NumberInput::parseToFloat($itemArray['credit'] ?? $itemArray['credit_display'] ?? 0);
            
            return $debit > 0 || $credit > 0;
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
            $itemArray = (array) $item;
            $debit = NumberInput::parseToFloat($itemArray['debit'] ?? $itemArray['debit_display'] ?? 0);
            $credit = NumberInput::parseToFloat($itemArray['credit'] ?? $itemArray['credit_display'] ?? 0);
            
            if ($debit <= 0 && $credit <= 0) {
                $this->NotificationHalt(__('Item :index must have a debit or credit value.', ['index' => $index + 1]));
            }
            
            if ($debit > 0 && $credit > 0) {
                $this->NotificationHalt(__('Item :index cannot have both debit and credit values.', ['index' => $index + 1]));
            }
        }
        
        $data['items'] = $validItems;
        
        $data['amount'] = $totals['total_debit'];
        $data['total_amount'] = $totals['total_debit'];
        
        $data['is_posted'] = false;
        $data['status'] = 'draft';
        $data['created_by_user_id'] = Filament::auth()->id();
        
        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                if (empty($item['cost_center_id'])) {
                    $data['items'][$index]['cost_center_id'] = null;
                }
            }
        }

        return $data;
    }

    protected function NotificationHalt(string $message): void
    {
        \Filament\Notifications\Notification::make()
            ->title('Validation Error')
            ->body($message)
            ->danger()
            ->send();
            
        $this->halt();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
