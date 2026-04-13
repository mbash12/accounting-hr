<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Filament\Actions\ImportPurchaseOrderWithItemsAction;
use App\Filament\Actions\ExportPurchaseOrderWithItemsAction;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Actions\RegenerateJournalEntry;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("purchase_order_no")
                    ->searchable()
                    ->copyable()
                    ->weight("bold")
                    ->label(__("No. Pesanan Pembelian")),
                TextColumn::make("date")
                    ->date()
                    ->sortable()
                    ->label(__("Tanggal Pesanan")),
                TextColumn::make("supplier.name")
                    ->searchable()
                    ->label(__("Pemasok")),
                TextColumn::make("salesOrder.order_number")
                    ->searchable()
                    ->label(__("No. SO"))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make("salesOrder.job_number")
                    ->searchable()
                    ->label(__("No. Job"))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make("salesOrder.client_po_number")
                    ->searchable()
                    ->label(__("No. PO Pelanggan"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("salesOrder.jb_job_number")
                    ->searchable()
                    ->label(__("No. Job JB"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("total_amount")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Total")),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "draft" => __("Draft"),
                            "posted" => __("Posted"),
                            default => $state,
                        },
                    )
                    ->color(
                        fn(string $state): string => match ($state) {
                            "draft" => "gray",
                            "posted" => "success",
                            default => "gray",
                        },
                    ),
                TextColumn::make("department.name")
                    ->label(__("Departemen"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("createdByUser.name")
                    ->label(__("Dibuat Oleh"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("Dibuat Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Diperbarui Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Dihapus Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function ($query) {
                $selectedCompanyId = session("selected_company_id");
                if ($selectedCompanyId && $selectedCompanyId !== "all") {
                    $query->where(function ($query) use ($selectedCompanyId) {
                        $query->where("company_id", $selectedCompanyId)
                              ->orWhereNull("company_id");
                    });
                }
            })
            ->filters([
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = 'Dari: ' . $data['date_from'];
                        }
                        if ($data['date_until'] ?? null) {
                            $indicators[] = 'Sampai: ' . $data['date_until'];
                        }
                        return $indicators;
                    }),
                Filter::make('supplier')
                    ->form([
                        \Filament\Forms\Components\Select::make('supplier_id')
                            ->label('Pemasok')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\Contact::query()->where('is_supplier', true);
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query->limit(50)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\Contact::query()->where('is_supplier', true);
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query
                                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\Contact::find($value)?->name),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['supplier_id'],
                                fn (Builder $query, $supplierId): Builder => $query->where('supplier_id', $supplierId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['supplier_id']) {
                            return null;
                        }
                        $supplier = \App\Models\Contact::find($data['supplier_id']);
                        return 'Pemasok: ' . ($supplier?->name ?? $data['supplier_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('createGoodsReceipt')
                        ->label('Buat Penerimaan Barang')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->color('primary')
                        ->visible(function (PurchaseOrder $record): bool {
                            $meta = $record->receipt_meta ?: $record->computeReceiptMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0 && $record->status === 'posted';
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Penerimaan Barang')
                        ->modalDescription('Apakah Anda yakin ingin membuat penerimaan barang? Dokumen akan dibuat dengan status terkunci.')
                        ->modalSubmitActionLabel('Ya, Buat')
                        ->action(function (PurchaseOrder $record) {
                            $purchaseOrder = PurchaseOrder::query()
                                ->with(['items'])
                                ->findOrFail($record->id);

                            $items = [];
                            foreach ($purchaseOrder->items as $poItem) {
                                $remaining = max(0.0, (float) ($poItem->quantity ?? 0) - (float) ($poItem->received_quantity ?? 0));
                                if ($remaining <= 0) {
                                    continue;
                                }
                                $items[] = [
                                    'purchase_order_item_id' => $poItem->id,
                                    'product_id' => $poItem->product_id,
                                    'unit_id' => $poItem->unit_id,
                                    'quantity' => $remaining,
                                    'description' => $poItem->description,
                                ];
                            }

                            return DB::transaction(function () use ($purchaseOrder, $items) {
                                $goodsReceipt = GoodsReceipt::create([
                                    'date' => now(),
                                    'supplier_id' => $purchaseOrder->supplier_id,
                                    'purchase_order_id' => $purchaseOrder->id,
                                    'is_locked' => false,
                                    'status' => 'draft', // Create as draft initially
                                    'job_id' => $purchaseOrder->job_id,
                                    'company_id' => $purchaseOrder->company_id,
                                    'created_by_user_id' => auth()->id(),
                                ]);

                                foreach ($items as $item) {
                                    GoodsReceiptItem::create([
                                        'goods_receipt_id' => $goodsReceipt->id,
                                        'purchase_order_item_id' => $item['purchase_order_item_id'],
                                        'product_id' => $item['product_id'],
                                        'unit_id' => $item['unit_id'],
                                        'quantity' => $item['quantity'],
                                        'description' => $item['description'],
                                    ]);
                                }

                                $goodsReceipt->is_locked = true;
                                $goodsReceipt->save();

                                $purchaseOrder->refreshReceiptTracking();

                                return redirect()->to(GoodsReceiptResource::getUrl('edit', ['record' => $goodsReceipt]));
                            });
                        }),
                    Action::make('createPurchaseInvoice')
                        ->label('Buat Invoice Pembelian')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->visible(function (PurchaseOrder $record): bool {
                            $meta = $record->invoice_meta ?: $record->computeInvoiceMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0 && $record->status === 'posted';
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Invoice Pembelian')
                        ->modalDescription('Apakah Anda yakin ingin membuat invoice pembelian? Invoice akan dibuat dengan status terkunci.')
                        ->modalSubmitActionLabel('Ya, Buat')
                        ->action(function (PurchaseOrder $record) {
                            $purchaseOrder = PurchaseOrder::query()
                                ->with(['items'])
                                ->findOrFail($record->id);

                            $items = [];
                            $subtotal = 0;
                            foreach ($purchaseOrder->items as $poItem) {
                                $remaining = max(0.0, (float) ($poItem->quantity ?? 0) - (float) ($poItem->invoiced_quantity ?? 0));
                                if ($remaining <= 0) {
                                    continue;
                                }
                                $lineTotal = $remaining * $poItem->unit_price;
                                $subtotal += $lineTotal;
                                
                                $items[] = [
                                    'purchase_order_item_id' => $poItem->id,
                                    'product_id' => $poItem->product_id,
                                    'unit_id' => $poItem->unit_id,
                                    'quantity' => $remaining,
                                    'unit_price' => $poItem->unit_price,
                                    'tax_id' => $poItem->tax_id,
                                    'description' => $poItem->description,
                                    'total' => $lineTotal,
                                ];
                            }

                            // Calculate totals from purchase order proportionally
                            $originalSubtotal = $purchaseOrder->subtotal;
                            $ratio = $originalSubtotal > 0 ? $subtotal / $originalSubtotal : 1;
                            
                            $discount = $purchaseOrder->discount * $ratio;
                            $otherCharges = $purchaseOrder->other_charges * $ratio;
                            $taxAmount = $purchaseOrder->tax_amount * $ratio;
                            $total = $subtotal - $discount + $otherCharges + $taxAmount;

                            return DB::transaction(function () use ($purchaseOrder, $items, $subtotal, $discount, $otherCharges, $taxAmount, $total) {
                                $invoice = \App\Models\PurchaseInvoice::create([
                                    'date' => now(),
                                    'supplier_id' => $purchaseOrder->supplier_id,
                                    'purchase_order_id' => $purchaseOrder->id,
                                    'is_locked' => false,
                                    'status' => 'draft', // Create as draft initially
                                    'job_id' => $purchaseOrder->job_id,
                                    'company_id' => $purchaseOrder->company_id,
                                    'created_by_user_id' => auth()->id(),
                                    'subtotal' => $subtotal,
                                    'discount' => $discount,
                                    'other_charges' => $otherCharges,
                                    'tax_amount' => $taxAmount,
                                    'total' => $total,
                                    'paid_amount' => 0,
                                    'outstanding_amount' => $total,
                                ]);

                                foreach ($items as $item) {
                                    \App\Models\PurchaseInvoiceItem::create([
                                        'purchase_invoice_id' => $invoice->id,
                                        'purchase_order_item_id' => $item['purchase_order_item_id'],
                                        'product_id' => $item['product_id'],
                                        'unit_id' => $item['unit_id'],
                                        'quantity' => $item['quantity'],
                                        'unit_price' => $item['unit_price'],
                                        'tax_id' => $item['tax_id'],
                                        'description' => $item['description'],
                                        'total' => $item['total'],
                                    ]);
                                }

                                $invoice->is_locked = true;
                                $invoice->save();

                                $purchaseOrder->refreshInvoiceTracking();

                                return redirect(\App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource::getUrl('edit', ['record' => $invoice]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => PurchaseOrderResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                ImportPurchaseOrderWithItemsAction::make(),
                ExportPurchaseOrderWithItemsAction::make(),
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('changeStatus')
                        ->label('Ubah Status')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->form([
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'posted' => 'Posted',
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['status' => $data['status']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}