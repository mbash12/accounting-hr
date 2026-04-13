<?php

namespace App\Filament\Resources\JournalEntries\Pages;

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
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Simpan'))
                ->action(function (array $data) {
                    $this->reason = $data['reason'] ?? null;
                    $this->save();
                })
                ->modalHeading(__('Alasan Ubah'))
                ->modalWidth('md')
                ->form([
                    TextInput::make('reason')
                        ->label(__('Alasan'))
                        ->required()
                        ->placeholder(__('Alasan diisi minimal 5 karakter'))
                        ->minLength(5),
                ])
                ->modalSubmitActionLabel(__('Proses'))
                ->modalCancelActionLabel(__('Batal'))
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
            $this->NotificationHalt('Journal must have at least 2 items with account and debit/credit values. Current valid items: ' . count($validItems));
        }

        $totals = JournalEntryForm::calculateTotalsFromItems($validItems);

        foreach ($validItems as $index => $item) {
            $itemArray = (array) $item;
            $debit = NumberInput::parseToFloat($itemArray['debit'] ?? $itemArray['debit_display'] ?? 0);
            $credit = NumberInput::parseToFloat($itemArray['credit'] ?? $itemArray['credit_display'] ?? 0);
            
            if ($debit <= 0 && $credit <= 0) {
                $this->NotificationHalt(__('Item :index harus memiliki nilai debit atau kredit.', ['index' => $index + 1]));
            }
            
            if ($debit > 0 && $credit > 0) {
                $this->NotificationHalt(__('Item :index tidak boleh memiliki nilai debit dan kredit sekaligus.', ['index' => $index + 1]));
            }
            
            if (empty($item['cost_center_id'])) {
                $validItems[$index]['cost_center_id'] = null;
            }
        }

        $data['items'] = $validItems;
        $data['amount'] = $totals['total_debit'];
        $data['total_amount'] = $totals['total_debit'];
        
        $isPosted = (bool) ($data['is_posted'] ?? true);
        $data['is_posted'] = $isPosted;
        $data['status'] = $isPosted ? 'posted' : 'draft';

        $data['updated_by_user_id'] = \Illuminate\Support\Facades\Auth::id();

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
}
