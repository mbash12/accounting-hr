<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Forms\Components\NumberInput;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\Tax;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $discountPercentage = (float) ($data['discount_percentage'] ?? 0);
        $otherCharges = NumberInput::parseToFloat($data['other_charges'] ?? 0);
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

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft')
                ->requiresConfirmation()
                ->modalHeading('Approve Purchase Order')
                ->modalDescription('Are you sure you want to approve this purchase order?')
                ->modalSubmitActionLabel('Yes, Approve')
                ->action(function (PurchaseOrder $record) {
                    $record->update(['status' => 'approved']);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Approved')
                        ->body("Purchase Order {$record->purchase_order_no} has been approved.")
                        ->send();
                }),
            Action::make('post')
                ->label('Post')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'approved')
                ->requiresConfirmation()
                ->modalHeading('Post Purchase Order')
                ->modalDescription('Are you sure you want to post this purchase order? This will create journal entries.')
                ->modalSubmitActionLabel('Yes, Post')
                ->action(function (PurchaseOrder $record) {
                    $record->update(['status' => 'posted']);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Posted')
                        ->body("Purchase Order {$record->purchase_order_no} has been posted.")
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record instanceof PurchaseOrder) {
            $this->record->refresh();

            if ($this->record->items()->exists()) {
                $this->record->recalculateTotalsFromItems();
                $this->record->saveQuietly();
            }
        }
    }
}
