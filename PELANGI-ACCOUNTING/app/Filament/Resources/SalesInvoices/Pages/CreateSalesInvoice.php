<?php

namespace App\Filament\Resources\SalesInvoices\Pages;

use App\Filament\Forms\Components\NumberInput;
use App\Filament\Resources\SalesInvoices\SalesInvoiceResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateSalesInvoice extends CreateRecord
{
    protected static string $resource = SalesInvoiceResource::class;

    public function create(...$args): void
    {
        try {
            parent::create(...$args);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validasi gagal.');

            Notification::make()
                ->title(__('Gagal menyimpan'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Get items from raw Livewire data - Filament v4 doesn't include repeater in $data
        $rawData = $this->data ?? [];
        $items = $rawData['items'] ?? [];
        
        Log::debug('CreateSalesInvoice - Raw data items:', ['items' => $items]);
        
        // Recalculate all totals before saving
        $data = $this->recalculateTotals($data, $items);
        
        Log::debug('CreateSalesInvoice - Form data after recalculation:', $data);
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        Log::debug('CreateSalesInvoice - Data being saved:', $data);
        
        try {
            $record = parent::handleRecordCreation($data);
            
            Log::debug('CreateSalesInvoice - Record created:', $record->toArray());
            
            return $record;
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validasi gagal.');

            Notification::make()
                ->title(__('Gagal menyimpan'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        }
    }

    /**
     * Recalculate all invoice totals from items
     */
    private function recalculateTotals(array $data, array $items): array
    {
        $discountPercentage = (float) ($data['discount_percentage'] ?? 0);
        $otherCharges = NumberInput::parseToFloat($data['other_charges'] ?? 0);
        
        $subtotal = 0.0;
        $taxTotal = 0.0;

        Log::debug('CreateSalesInvoice - Calculating totals for items:', ['count' => count($items)]);

        foreach ($items as $key => $item) {
            $qty = NumberInput::parseToFloat($item['quantity'] ?? 0);
            $price = NumberInput::parseToFloat($item['unit_price'] ?? 0);
            $line = $qty * $price;
            $subtotal += $line;
            
            Log::debug("CreateSalesInvoice - Item {$key}:", [
                'qty' => $qty,
                'price' => $price,
                'line' => $line
            ]);
            
            $taxId = $item['tax_id'] ?? null;
            if ($taxId) {
                $tax = \App\Models\Tax::find($taxId);
                if ($tax && $tax->tax_percentage) {
                    $lineDiscount = $line * ($discountPercentage / 100);
                    $taxBase = $line - $lineDiscount;
                    $taxTotal += $taxBase * ((float) $tax->tax_percentage) / 100;
                }
            }
        }

        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount + $otherCharges + $taxTotal;
        $paidAmount = NumberInput::parseToFloat($data['paid_amount'] ?? 0);
        $outstanding = $total - $paidAmount;

        // Update data with calculated values
        $data['subtotal'] = $subtotal;
        $data['discount'] = $discountAmount;
        $data['tax_amount'] = $taxTotal;
        $data['total_amount'] = $total;
        $data['outstanding_amount'] = $outstanding;

        Log::debug('CreateSalesInvoice - Calculated totals:', [
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'tax' => $taxTotal,
            'total' => $total,
            'outstanding' => $outstanding
        ]);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
