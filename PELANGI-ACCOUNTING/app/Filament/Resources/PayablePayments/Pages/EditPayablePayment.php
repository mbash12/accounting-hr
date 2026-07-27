<?php

namespace App\Filament\Resources\PayablePayments\Pages;

use App\Filament\Resources\PayablePayments\PayablePaymentResource;
use App\Models\PayablePaymentItem;
use App\Services\ReceivablePayableService;
use App\Traits\WarnEditPostedRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EditPayablePayment extends EditRecord
{
    use WarnEditPostedRecord;

    protected static string $resource = PayablePaymentResource::class;

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
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Save Failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
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
                    'purchase_invoice_id' => $item->purchase_invoice_id,
                    'date' => $item->date,
                    'amount' => $item->amount,
                    'paid_amount' => $item->paid_amount,
                    'discount_amount' => $item->discount_amount,
                    'write_off_amount' => $item->write_off_amount,
                    'set_payment' => $item->set_payment,
                ];
            })->toArray();
            
            $data['items'] = $items;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record && $this->record->exists) {
            $originalUpdatedAt = $this->record->getOriginal('updated_at');
            $currentUpdatedAt = $this->record->updated_at;
            
            if ($originalUpdatedAt && $currentUpdatedAt && $currentUpdatedAt->gt($originalUpdatedAt)) {
                throw ValidationException::withMessages([
                    'items' => __('Data has been modified by another user or process. Please refresh the page and try again.'),
                ]);
            }
        }

        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => __('At least one item is required.'),
            ]);
        }

        $invoiceIds = [];
        foreach ($items as $index => $item) {
            if (empty($item['purchase_invoice_id'])) {
                throw ValidationException::withMessages([
                    "items.{$index}.purchase_invoice_id" => __('Invoice number must be selected for each item.'),
                ]);
            }
            
            if (in_array($item['purchase_invoice_id'], $invoiceIds)) {
                throw ValidationException::withMessages([
                    "items.{$index}.purchase_invoice_id" => __('Invoice number cannot be duplicated. This invoice is already used in another item.'),
                ]);
            }
            
            $invoiceIds[] = $item['purchase_invoice_id'];
        }

        $total = 0;
        foreach ($items as $item) {
            $total += \App\Filament\Forms\Components\NumberInput::parseToFloat($item['set_payment'] ?? 0);
        }

        $otherCosts = \App\Filament\Forms\Components\NumberInput::parseToFloat($data['other_costs'] ?? 0);
        $total += $otherCosts;

        $data['total_payment'] = $total;

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

        if (empty($data['other_costs_account_id']) && !empty($data['company_id'])) {
            $mapping = \App\Models\AccountMapping::getAccountMapping(
                'payable_payment',
                'other_costs',
                $data['company_id']
            );
            
            if ($mapping) {
                $data['other_costs_account_id'] = $mapping->id;
            } else {
                $query = \App\Models\Account::where('is_header', false)
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('code', 'like', '2101%')
                            ->orWhere('name', 'like', '%Advance Purchase%')
                            ->orWhere('name', 'like', '%Accounts Payable%');
                    })
                    ->where(function ($q) use ($data) {
                        $q->where('company_id', $data['company_id'])
                            ->orWhereNull('company_id');
                    });
                
                $account = $query->orderBy('code')->first();
                if ($account) {
                    $data['other_costs_account_id'] = $account->id;
                }
            }
        }

        $data['updated_by_user_id'] = Auth::id();
        
        if (empty($data['status']) || $data['status'] === 'pending') {
            $data['status'] = 'completed';
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $items = $this->data['items'] ?? [];
        
        if (!empty($items) && $this->record) {
            $oldItems = $this->record->items()->get();
            foreach ($oldItems as $oldItem) {
                $invoice = \App\Models\PurchaseInvoice::find($oldItem->purchase_invoice_id);
                if ($invoice) {
                    $newPaidAmount = max(0, (float) $invoice->paid_amount - (float) $oldItem->set_payment);
                    $invoiceTotal = (float) $invoice->total;
                    $newOutstandingAmount = $invoiceTotal - $newPaidAmount;
                    
                    $invoice->paid_amount = $newPaidAmount;
                    $invoice->outstanding_amount = max(0, $newOutstandingAmount);
                    $invoice->is_paid = $newOutstandingAmount <= 0;
                    
                    if ($invoice->is_paid) {
                        $invoice->status = 'paid';
                    } elseif ($newPaidAmount > 0) {
                        $invoice->status = 'partially_paid';
                    } else {
                        $invoice->status = 'received';
                    }
                    
                    $invoice->save();
                    
                    try {
                        $service = app(ReceivablePayableService::class);
                        $service->updateOutstandingJournalEntryForPurchaseInvoice($invoice);
                    } catch (\Exception $e) {
                        \Log::error('Error updating outstanding journal entry for PurchaseInvoice: ' . $e->getMessage(), [
                            'invoice_id' => $invoice->id,
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                }
            }

            $this->record->items()->delete();
            
            foreach ($items as $item) {
                $setPayment = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['set_payment'] ?? 0);
                $discountAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['discount_amount'] ?? 0);
                $writeOffAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['write_off_amount'] ?? 0);
                $amount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['amount'] ?? 0);
                $paidAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['paid_amount'] ?? 0);

                PayablePaymentItem::create([
                    'payable_payment_id' => $this->record->id,
                    'purchase_invoice_id' => $item['purchase_invoice_id'],
                    'date' => $item['date'],
                    'amount' => $amount,
                    'paid_amount' => $paidAmount,
                    'discount_amount' => $discountAmount,
                    'write_off_amount' => $writeOffAmount,
                    'set_payment' => $setPayment,
                ]);

                $invoice = \App\Models\PurchaseInvoice::find($item['purchase_invoice_id']);
                if ($invoice) {
                    $newPaidAmount = (float) $invoice->paid_amount + $setPayment;
                    $invoiceTotal = (float) $invoice->total;
                    $newOutstandingAmount = $invoiceTotal - $newPaidAmount;
                    
                    $invoice->paid_amount = $newPaidAmount;
                    $invoice->outstanding_amount = max(0, $newOutstandingAmount);
                    $invoice->is_paid = $newOutstandingAmount <= 0;
                    
                    if ($invoice->is_paid) {
                        $invoice->status = 'paid';
                    } elseif ($newPaidAmount > 0) {
                        $invoice->status = 'partially_paid';
                    }
                    
                    $invoice->save();
                    
                    try {
                        $service = app(ReceivablePayableService::class);
                        $service->updateOutstandingJournalEntryForPurchaseInvoice($invoice);
                    } catch (\Exception $e) {
                        \Log::error('Error updating outstanding journal entry for PurchaseInvoice: ' . $e->getMessage(), [
                            'invoice_id' => $invoice->id,
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                }
            }

            // Keep journal entry in sync for both draft and completed payments
            if ($this->record && in_array($this->record->status, ['draft', 'completed'])) {
                try {
                    $service = app(ReceivablePayableService::class);
                    $entry = $service->createJournalEntryForPayablePayment($this->record);
                    if ($entry === null) {
                        Notification::make()
                            ->warning()
                            ->title(__('Journal entry skipped'))
                            ->body(__('Payment saved successfully, but no journal was created. Configure Accounts Payable mapping for Payable Payment.'))
                            ->send();
                    }
                } catch (\Exception $e) {
                    \Log::error('Error updating journal entry for payable payment: ' . $e->getMessage(), [
                        'payment_id' => $this->record->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                    Notification::make()
                        ->danger()
                        ->title(__('Journal Entry Error'))
                        ->body(__('Payment saved but journal entry update failed: :message', ['message' => $e->getMessage()]))
                        ->persistent()
                        ->send();
                }
            } else {
                try {
                    $service = app(ReceivablePayableService::class);
                    $service->deleteJournalEntryForPayablePayment($this->record);
                } catch (\Exception $e) {
                    \Log::error('Error deleting journal entry for payable payment: ' . $e->getMessage());
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

