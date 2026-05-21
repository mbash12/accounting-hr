<?php

namespace App\Filament\Resources\ReceivablePayments\Pages;

use App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource;
use App\Models\ReceivablePayment;
use App\Models\ReceivablePaymentItem;
use App\Services\CodeGeneratorService;
use App\Services\ReceivablePayableService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateReceivablePayment extends CreateRecord
{
    protected static string $resource = ReceivablePaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $cacheKey = 'receivable_payment_creating_' . Auth::id() . '_' . request()->ip();
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
            if (empty($item['sales_invoice_id'])) {
                throw ValidationException::withMessages([
                    "items.{$index}.sales_invoice_id" => __('Invoice number must be selected for each item.'),
                ]);
            }
            
            if (in_array($item['sales_invoice_id'], $invoiceIds)) {
                throw ValidationException::withMessages([
                    "items.{$index}.sales_invoice_id" => __('Invoice number cannot be duplicated. This invoice is already used in another item.'),
                ]);
            }
            
            $invoiceIds[] = $item['sales_invoice_id'];
        }

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

        if (empty($data['other_costs_account_id']) && !empty($data['company_id'])) {
            $mapping = \App\Models\AccountMapping::getAccountMapping(
                'receivable_payment',
                'other_costs',
                $data['company_id']
            );
            
            if ($mapping) {
                $data['other_costs_account_id'] = $mapping->id;
            } else {
                $query = \App\Models\Account::where('is_header', false)
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('code', 'like', '2102%')
                            ->orWhere('name', 'like', '%Uang Muka Penjualan%')
                            ->orWhere('name', 'like', '%Advance Sales%');
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

        // Generate payment_number if not provided
        if (empty($data['payment_number'])) {
            $codeService = app(CodeGeneratorService::class);
            $data['payment_number'] = $codeService->generateCode(
                'receivable_payment',
                $data['company_id'] ?? null
            );
        }

        $data['created_by_user_id'] = Auth::id();
        $data['updated_by_user_id'] = Auth::id();
        
        // Set status jadi completed setelah submit
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

        // Clear cache after successful validation
        cache()->forget($cacheKey);

        return $data;
    }

    protected function afterCreate(): void
    {
        $cacheKey = 'receivable_payment_creating_' . Auth::id() . '_' . request()->ip();
        cache()->forget($cacheKey);

        $items = $this->data['items'] ?? [];
        
        if (!empty($items) && $this->record) {
            foreach ($items as $item) {
                $setPayment = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['set_payment'] ?? 0);
                $discountAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['discount_amount'] ?? 0);
                $writeOffAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['write_off_amount'] ?? 0);
                $amount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['amount'] ?? 0);
                $paidAmount = \App\Filament\Forms\Components\NumberInput::parseToFloat($item['paid_amount'] ?? 0);

                ReceivablePaymentItem::create([
                    'receivable_payment_id' => $this->record->id,
                    'sales_invoice_id' => $item['sales_invoice_id'],
                    'date' => $item['date'],
                    'amount' => $amount,
                    'paid_amount' => $paidAmount,
                    'discount_amount' => $discountAmount,
                    'write_off_amount' => $writeOffAmount,
                    'set_payment' => $setPayment,
                ]);

                $invoice = \App\Models\SalesInvoice::find($item['sales_invoice_id']);
                if ($invoice) {
                    $newPaidAmount = $invoice->paid_amount + $setPayment;
                    $newOutstandingAmount = $invoice->total_amount - $newPaidAmount;
                    
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
                        $service->updateOutstandingJournalEntryForSalesInvoice($invoice);
                    } catch (\Exception $e) {
                        \Log::error('Error updating outstanding journal entry for SalesInvoice: ' . $e->getMessage(), [
                            'invoice_id' => $invoice->id,
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                }
            }

            if ($this->record && $this->record->status === 'completed') {
                try {
                    $service = app(ReceivablePayableService::class);
                    $service->createJournalEntryForReceivablePayment($this->record);
                } catch (\Exception $e) {
                    \Log::error('Error creating journal entry for receivable payment: ' . $e->getMessage(), [
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



