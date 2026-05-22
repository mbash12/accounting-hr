<?php

namespace App\Filament\Resources\CashReceipts\Pages;

use App\Filament\Resources\CashReceipts\CashReceiptResource;
use App\Models\CashReceipt;
use App\Models\CashReceiptItem;
use App\Services\CashBankService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditCashReceipt extends EditRecord
{
    protected static string $resource = CashReceiptResource::class;

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

    protected ?string $oldStatus = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldStatus = $this->record->status;

        $isPosted = $data['is_posted'] ?? false;
        $data['status'] = $isPosted ? 'posted' : 'draft';
        unset($data['is_posted']); // Remove toggle from data

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

        $data['updated_by_user_id'] = Auth::id();

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
                
                CashReceiptItem::create([
                    'cash_receipt_id' => $this->record->id,
                    'account_id' => $item['account_id'],
                    'amount' => $itemTotal,
                    'description' => $item['description'] ?? null,
                ]);
            }
        }

        $oldStatus = $this->oldStatus;
        $this->record->refresh();
        $newStatus = $this->record->status;

        if ($oldStatus !== $newStatus || $newStatus === 'posted') {
            DB::transaction(function () {
                $service = app(CashBankService::class);
                $service->createJournalEntryForRecord($this->record);
            });
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
