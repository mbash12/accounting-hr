<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use App\Filament\Forms\Components\NumberInput;
use App\Services\AdditionalChargesHelper;
use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Models\SalesOrder;
use App\Models\Tax;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditSalesOrder extends EditRecord
{
    protected static string $resource = SalesOrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

        $data['subtotal'] = $subtotal;
        $data['discount'] = $discountAmount;
        $data['tax_amount'] = $taxTotal;
        $data['total_amount'] = $total;
        $data['other_charges'] = $otherCharges;

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
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
