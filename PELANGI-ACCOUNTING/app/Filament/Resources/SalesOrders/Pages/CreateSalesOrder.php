<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use App\Filament\Forms\Components\NumberInput;
use App\Services\AdditionalChargesHelper;
use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Models\SalesOrder;
use App\Models\Tax;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateSalesOrder extends CreateRecord
{
    protected static string $resource = SalesOrderResource::class;

    public function create(...$args): void
    {
        try {
            parent::create(...$args);
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        \Log::info('Sales Order Create - Before Calculation', $data);

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

        $discountPercentage = (float) ($data['discount_percentage'] ?? 0);
        $otherCharges = AdditionalChargesHelper::resolveAmount(
            $data['otherCharges'] ?? null,
            $data['other_charges'] ?? 0,
        );
        $items = $data['items'] ?? [];

        $subtotal = 0.0;
        $taxTotal = 0.0;

        foreach ($items as $index => $item) {
            $quantity = NumberInput::parseToFloat($item['quantity'] ?? 0);
            $unitPrice = NumberInput::parseToFloat($item['unit_price'] ?? 0);
            $lineTotal = $quantity * $unitPrice;

            $data['items'][$index]['quantity'] = $quantity;
            $data['items'][$index]['unit_price'] = $unitPrice;
            $data['items'][$index]['total'] = $lineTotal;

            $subtotal += $lineTotal;

            $taxId = $item['tax_id'] ?? null;
            if ($taxId) {
                $tax = Tax::find($taxId);
                if ($tax && isset($tax->tax_percentage)) {
                    $lineDiscount = $lineTotal * ($discountPercentage / 100);
                    $taxBase = $lineTotal - $lineDiscount;
                    $taxTotal += $taxBase * ((float) $tax->tax_percentage) / 100;
                }
            }
        }

        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount + $otherCharges + $taxTotal;

        // Set calculated totals
        $data['subtotal'] = $subtotal;
        $data['discount'] = $discountAmount;
        $data['tax_amount'] = $taxTotal;
        $data['total_amount'] = $total;
        $data['other_charges'] = $otherCharges;

        \Log::info('Sales Order Create - After Calculation', $data);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof SalesOrder) {
            $this->record->refresh();

            $this->record->syncOtherChargesTotal();
            if ($this->record->items()->exists()) {
                $this->record->recalculateTotalsFromItems();
            }
            $this->record->saveQuietly();
        }
    }
}
