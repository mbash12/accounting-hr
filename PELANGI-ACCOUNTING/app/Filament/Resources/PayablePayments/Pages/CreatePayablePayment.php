<?php

namespace App\Filament\Resources\PayablePayments\Pages;

use App\Filament\Resources\PayablePayments\PayablePaymentResource;
use App\Models\PayablePayment;
use App\Models\PayablePaymentItem;
use App\Services\CodeGeneratorService;
use App\Services\ReceivablePayableService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreatePayablePayment extends CreateRecord
{
    protected static string $resource = PayablePaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $cacheKey = 'payable_payment_creating_' . Auth::id() . '_' . request()->ip();
        if (cache()->has($cacheKey)) {
            throw ValidationException::withMessages([
                'items' => __('Save is in progress. Please wait a moment.'),
            ]);
        }
        
        cache()->put($cacheKey, true, 10);

        $items = $data['items'] ?? [];
        if (empty($items)) {
            cache()->forget($cacheKey);
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
                            ->orWhere('name', 'like', '%Uang Muka Pembelian%')
                            ->orWhere('name', 'like', '%Advance Purchase%')
                            ->orWhere('name', 'like', '%Hutang Usaha%');
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

        if (empty($data['payment_number'])) {
            $codeService = app(CodeGeneratorService::class);
            $data['payment_number'] = $codeService->generateCode(
                'payable_payment',
                $data['company_id'] ?? null
            );
        }

        $data['created_by_user_id'] = Auth::id();
        $data['updated_by_user_id'] = Auth::id();
        
        if (empty($data['status']) || $data['status'] === 'pending') {
            $data['status'] = 'completed';
        }

        $total = 0;
        foreach ($items as $item) {
            $total += \App\Filament\Forms\Components\NumberInput::parseToFloat($item['set_payment'] ?? 0);
        }

        $otherCosts = \App\Filament\Forms\Components\NumberInput::parseToFloat($data['other_costs'] ?? 0);
        $total += $otherCosts;

        $data['total_payment'] = $total;

        cache()->forget($cacheKey);

        return $data;
    }

    protected function afterCreate(): void
    {
        $cacheKey = 'payable_payment_creating_' . Auth::id() . '_' . request()->ip();
        cache()->forget($cacheKey);

        $items = $this->data['items'] ?? [];
        
        if (!empty($items) && $this->record) {
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

            if ($this->record && $this->record->status === 'completed') {
                try {
                    $service = app(ReceivablePayableService::class);
                    $service->createJournalEntryForPayablePayment($this->record);
                } catch (\Exception $e) {
                    \Log::error('Error creating journal entry for payable payment: ' . $e->getMessage(), [
                        'payment_id' => $this->record->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

