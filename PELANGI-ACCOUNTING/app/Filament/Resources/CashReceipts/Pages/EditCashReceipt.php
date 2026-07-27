<?php

namespace App\Filament\Resources\CashReceipts\Pages;

use App\Filament\Resources\CashReceipts\CashReceiptResource;
use App\Models\CashReceipt;
use App\Models\CashReceiptItem;
use App\Services\CashBankService;
use App\Traits\WarnEditPostedRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditCashReceipt extends EditRecord
{
    use WarnEditPostedRecord;

    protected static string $resource = CashReceiptResource::class;

    public function save(...$args): void
    {
        try {
            parent::save(...$args);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validation failed.');

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        } catch (QueryException $e) {
            $message = $this->resolveErrorMessage($e);

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'receipt_number' => $message,
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Save Failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function resolveErrorMessage(QueryException $e): string
    {
        $sqlState = $e->getSQLState() ?? '';
        $message = $e->getMessage();

        if (str_contains($message, 'cash_receipts_company_receipt_number_unique') ||
            str_contains($message, 'cash_receipts_receipt_number_unique') ||
            $sqlState === '23505') {
            return __('Receipt number is already used. Please generate a new code or use a different one.');
        }

        return __('An error occurred while saving. Please try again.');
    }

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

        // Journal entry creation is handled by Posting Center
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
