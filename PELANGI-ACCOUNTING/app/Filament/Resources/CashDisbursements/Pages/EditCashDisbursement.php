<?php

namespace App\Filament\Resources\CashDisbursements\Pages;

use App\Filament\Resources\CashDisbursements\CashDisbursementResource;
use App\Models\CashDisbursement;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditCashDisbursement extends EditRecord
{
    protected static string $resource = CashDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record && $this->record->exists) {
            $items = $this->record->items()->get()->map(function ($item) {
                return [
                    'account_id' => $item->account_id,
                    'amount' => (float) ($item->amount ?? 0), 
                    'description' => $item->description,
                ];
            })->toArray();
            
            $data['items'] = $items;
            
            $total = 0;
            foreach ($items as $item) {
                $total += (float) ($item['amount'] ?? 0);
            }
            $data['total'] = $total > 0 ? $total : ($this->record->total ?? 0);
        }

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Preserve existing status - use Posting Center to post
        unset($data['is_posted']);

        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => __('At least one item is required.'),
            ]);
        }

        $total = 0;
        foreach ($items as $item) {
            $itemTotal = $item['amount'] ?? 0;
            if (is_string($itemTotal)) {
                $itemTotal = str_replace(['.', ','], ['', '.'], $itemTotal);
            }
            $total += is_numeric($itemTotal) ? (float) $itemTotal : 0;
        }
        $data['total'] = $total;


        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

        $data['updated_by_user_id'] = auth()->id();

        return $data;
    }

    protected function afterSave(): void
    {
        $items = $this->data['items'] ?? [];
        
        if (!empty($items) && $this->record) {
            $this->record->items()->delete();
            
            foreach ($items as $item) {
                $itemTotal = $item['amount'] ?? 0;
                if (is_string($itemTotal)) {
                    $itemTotal = str_replace(['.', ','], ['', '.'], $itemTotal);
                }
                $itemTotal = is_numeric($itemTotal) ? (float) $itemTotal : 0;
                
                \App\Models\CashDisbursementItem::create([
                    'cash_disbursement_id' => $this->record->id,
                    'account_id' => $item['account_id'],
                    'amount' => $itemTotal,
                    'description' => $item['description'] ?? null,
                ]);
            }
        }


        // Journal entry creation is handled by Posting Center
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
