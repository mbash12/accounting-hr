<?php

namespace App\Filament\Actions;

use App\Models\GoodsReceipt;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class CreatePurchaseInvoiceFromGoodsReceipt extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make('createPurchaseInvoice');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Create Purchase Invoice')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->visible(function (GoodsReceipt $record): bool {
                if (!$record->is_locked) return false;
                // Hide if all items are fully returned
                if ($record->computeReturnMeta()['remaining'] <= 0) return false;
                // Guard against duplicate invoices
                return !$record->purchaseInvoices()->where('is_locked', true)->exists();
            })
            ->requiresConfirmation()
            ->modalHeading('Create Purchase Invoice')
            ->modalDescription('Create a purchase invoice from this goods receipt? The invoice will be created in locked status.')
            ->modalSubmitActionLabel('Yes, Create')
            ->action(function (GoodsReceipt $record) {
                $receipt = GoodsReceipt::query()
                    ->with(['items.purchaseOrderItem.tax', 'purchaseOrder'])
                    ->findOrFail($record->id);

                $po = $receipt->purchaseOrder;
                $items = [];
                $subtotal = 0;
                $totalTax = 0;

                foreach ($receipt->items as $grItem) {
                    $poItem = $grItem->purchaseOrderItem;
                    $receivedQty = (float) ($grItem->quantity ?? 0) - (float) ($grItem->returned_quantity ?? 0);
                    if ($receivedQty <= 0) continue;

                    $unitPrice = $poItem ? (float) ($poItem->unit_price ?? 0) : 0;
                    $lineTotal = $receivedQty * $unitPrice;

                    // Calculate tax per line item from tax rate
                    $taxRate = $poItem && $poItem->tax ? (float) ($poItem->tax->tax_percentage ?? 0) : 0;
                    $lineTax = $lineTotal * ($taxRate / 100);

                    $subtotal += $lineTotal;
                    $totalTax += $lineTax;

                    $items[] = [
                        'goods_receipt_item_id' => $grItem->id,
                        'purchase_order_item_id' => $grItem->purchase_order_item_id,
                        'product_id' => $grItem->product_id,
                        'unit_id' => $grItem->unit_id ?? ($poItem->unit_id ?? null),
                        'quantity' => $receivedQty,
                        'unit_price' => $unitPrice,
                        'tax_id' => $poItem->tax_id ?? null,
                        'description' => $grItem->description ?? ($poItem->description ?? null),
                        'total' => $lineTotal,
                        'conversion_factor' => $grItem->conversion_factor ?? 1,
                        'base_quantity' => $grItem->base_quantity ?? $receivedQty,
                    ];
                }

                $discountPercentage = $po->discount_percentage ?? 0;
                $discount = $subtotal * ($discountPercentage / 100);
                $otherCharges = $po->other_charges ?? 0;
                $total = $subtotal - $discount + $otherCharges + $totalTax;

                return DB::transaction(function () use ($receipt, $po, $items, $subtotal, $discount, $otherCharges, $totalTax, $total) {
                    $invoice = PurchaseInvoice::create([
                        'date' => now(),
                        'supplier_id' => $receipt->supplier_id,
                        'purchase_order_id' => $receipt->purchase_order_id,
                        'goods_receipt_id' => $receipt->id,
                        'is_locked' => false,
                        'status' => 'draft',
                        'company_id' => $receipt->company_id,
                        'created_by_user_id' => auth()->id(),
                        'subtotal' => $subtotal,
                        'discount' => $discount,
                        'other_charges' => $otherCharges,
                        'tax_amount' => $totalTax,
                        'total' => $total,
                        'paid_amount' => 0,
                        'outstanding_amount' => $total,
                    ]);

                    foreach ($items as $item) {
                        PurchaseInvoiceItem::create([
                            'purchase_invoice_id' => $invoice->id,
                            'goods_receipt_item_id' => $item['goods_receipt_item_id'],
                            'purchase_order_item_id' => $item['purchase_order_item_id'],
                            'product_id' => $item['product_id'],
                            'unit_id' => $item['unit_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'tax_id' => $item['tax_id'],
                            'description' => $item['description'],
                            'total' => $item['total'],
                            'conversion_factor' => $item['conversion_factor'],
                            'base_quantity' => $item['base_quantity'],
                        ]);
                    }

                    $invoice->is_locked = true;
                    $invoice->save();

                    if ($po) {
                        $po->refreshInvoiceTracking();
                    }

                    return redirect(\App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource::getUrl('edit', ['record' => $invoice]));
                });
            });
    }
}
