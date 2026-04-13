<?php

namespace App\Filament\Resources\SalesOrders\Tables;

use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\SalesOrder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Actions\RegenerateJournalEntry;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SalesOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("order_number")
                    ->searchable()
                    ->copyable()
                    ->weight("bold")
                    ->label(__("No. Pesanan")),
                TextColumn::make("date")
                    ->date()
                    ->sortable()
                    ->label(__("Tanggal Pesanan")),
                TextColumn::make("customer.name")
                    ->searchable()
                    ->label(__("Pelanggan")),
                TextColumn::make("job_number")
                    ->searchable()
                    ->label(__("No. Job")),
                TextColumn::make("client_po_number")
                    ->searchable()
                    ->label(__("No. PO Pelanggan"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("jb_job_number")
                    ->searchable()
                    ->label(__("No. Job JB"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("products_list")
                    ->label(__("Produk"))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->whereHas('items', function ($q2) use ($search) {
                                $q2->where(function ($q3) use ($search) {
                                    $q3->whereRaw('LOWER(item_name) LIKE ?', ['%' . strtolower($search) . '%'])
                                       ->orWhereHas('product', function ($q4) use ($search) {
                                           $q4->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                                       });
                                });
                            });
                        });
                    })
                    ->getStateUsing(function ($record): string {
                        $names = $record->items
                            ->map(fn ($item) => $item->item_name ?? $item->product?->name)
                            ->filter(fn ($name) => $name !== null && $name !== '')
                            ->unique()
                            ->values();

                        return $names->isNotEmpty() ? $names->implode(', ') : '-';
                    })
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make("order_type")
                    ->label(__("Jenis Pesanan"))
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "deposit" => __("Deposit"),
                            "standar" => __("Standar"),
                            "aktual" => __("Aktual"),
                            default => $state,
                        },
                    )
                    ->color(
                        fn(string $state): string => match ($state) {
                            "deposit" => "warning",
                            "standar" => "primary",
                            "aktual" => "success",
                            default => "gray",
                        },
                    ),
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
                $query->with(['items.product']);
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
                Filter::make('customer')
                    ->form([
                        \Filament\Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\Contact::query()->where('is_customer', true);
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
                                $query = \App\Models\Contact::query()->where('is_customer', true);
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
                                $data['customer_id'],
                                fn (Builder $query, $customerId): Builder => $query->where('customer_id', $customerId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['customer_id']) {
                            return null;
                        }
                        $customer = \App\Models\Contact::find($data['customer_id']);
                        return 'Pelanggan: ' . ($customer?->name ?? $data['customer_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    // Status edit action - available when locked
                    Action::make('editStatus')
                        ->label('Edit Status')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->visible(fn (SalesOrder $record): bool => SalesOrderResource::isLocked())
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'posted' => 'Posted',
                                ])
                                ->required(),
                        ])
                        ->fillForm(fn (SalesOrder $record): array => [
                            'status' => $record->status,
                        ])
                        ->action(function (SalesOrder $record, array $data): void {
                            $record->update(['status' => $data['status']]);
                        })
                        ->requiresConfirmation(false),
                    // Action::make('createDeliveryGoods')
                    //     ->label('Buat Pengiriman (Barang)')
                    //     ->icon('heroicon-o-truck')
                    //     ->color('primary')
                    //     ->visible(function (SalesOrder $record): bool {
                    //         $meta = $record->delivery_meta ?: $record->computeDeliveryMeta();
                    //         return (float) ($meta['goods']['remaining'] ?? 0) > 0 && $record->status === 'posted';
                    //     })
                    //     ->requiresConfirmation()
                    //     ->modalHeading('Buat Pengiriman Barang')
                    //     ->modalDescription('Apakah Anda yakin ingin membuat dokumen pengiriman barang? Dokumen akan dibuat dengan status terkunci.')
                    //     ->modalSubmitActionLabel('Ya, Buat')
                    //     ->action(function (SalesOrder $record) {
                    //         $salesOrder = SalesOrder::query()
                    //             ->with(['items.product.productGroup'])
                    //             ->findOrFail($record->id);

                    //         $items = [];
                    //         foreach ($salesOrder->items as $soItem) {
                    //             $shippingType = $soItem->product?->productGroup?->shipping_type;
                    //             if ($shippingType === 'digital') {
                    //                 continue;
                    //             }
                    //             $remaining = max(0.0, (float) ($soItem->quantity ?? 0) - (float) ($soItem->delivered_quantity ?? 0));
                    //             if ($remaining <= 0) {
                    //                 continue;
                    //             }
                    //             $items[] = [
                    //                 'sales_order_item_id' => $soItem->id,
                    //                 'product_id' => $soItem->product_id,
                    //                 'unit_id' => $soItem->unit_id,
                    //                 'quantity' => $remaining,
                    //                 'description' => $soItem->description,
                    //             ];
                    //         }

                    //         return DB::transaction(function () use ($salesOrder, $items) {
                    //             $delivery = DeliveryDocument::create([
                    //                 'delivery_type' => 'goods',
                    //                 'date' => now(),
                    //                 'customer_id' => $salesOrder->customer_id,
                    //                 'sales_order_id' => $salesOrder->id,
                    //                 'is_locked' => false,
                    //                 'status' => 'draft', // Create as draft initially
                    //                 'job_id' => $salesOrder->job_id,
                    //                 'company_id' => $salesOrder->company_id,
                    //                 'created_by_user_id' => auth()->id(),
                    //             ]);

                    //             foreach ($items as $item) {
                    //                 DeliveryDocumentItem::create([
                    //                     'delivery_document_id' => $delivery->id,
                    //                     'sales_order_item_id' => $item['sales_order_item_id'],
                    //                     'product_id' => $item['product_id'],
                    //                     'unit_id' => $item['unit_id'],
                    //                     'quantity' => $item['quantity'],
                    //                     'description' => $item['description'],
                    //                 ]);
                    //             }

                    //             $delivery->is_locked = true;
                    //             $delivery->save();

                    //             $salesOrder->refreshDeliveryTracking();

                    //             return redirect(SalesDeliveryResource::getUrl('edit', ['record' => $delivery]));
                    //         });
                    //     }),
                    // Action::make('createDeliveryDocument')
                    //     ->label('Buat Pengiriman (Dokumen)')
                    //     ->icon('heroicon-o-document-text')
                    //     ->color('primary')
                    //     ->visible(function (SalesOrder $record): bool {
                    //         $meta = $record->delivery_meta ?: $record->computeDeliveryMeta();
                    //         return (float) ($meta['document']['remaining'] ?? 0) > 0 && $record->status === 'posted';
                    //     })
                    //     ->requiresConfirmation()
                    //     ->modalHeading('Buat Pengiriman Dokumen')
                    //     ->modalDescription('Apakah Anda yakin ingin membuat dokumen pengiriman dokumen? Dokumen akan dibuat dengan status terkunci.')
                    //     ->modalSubmitActionLabel('Ya, Buat')
                    //     ->action(function (SalesOrder $record) {
                    //         $salesOrder = SalesOrder::query()
                    //             ->with(['items.product.productGroup'])
                    //             ->findOrFail($record->id);

                    //         $items = [];
                    //         foreach ($salesOrder->items as $soItem) {
                    //             $shippingType = $soItem->product?->productGroup?->shipping_type;
                    //             if ($shippingType !== 'digital') {
                    //                 continue;
                    //             }
                    //             $remaining = max(0.0, (float) ($soItem->quantity ?? 0) - (float) ($soItem->delivered_quantity ?? 0));
                    //             if ($remaining <= 0) {
                    //                 continue;
                    //             }
                    //             $items[] = [
                    //                 'sales_order_item_id' => $soItem->id,
                    //                 'product_id' => $soItem->product_id,
                    //                 'unit_id' => $soItem->unit_id,
                    //                 'quantity' => $remaining,
                    //                 'description' => $soItem->description,
                    //             ];
                    //         }

                    //         return DB::transaction(function () use ($salesOrder, $items) {
                    //             $delivery = DeliveryDocument::create([
                    //                 'delivery_type' => 'document',
                    //                 'date' => now(),
                    //                 'customer_id' => $salesOrder->customer_id,
                    //                 'sales_order_id' => $salesOrder->id,
                    //                 'is_locked' => false,
                    //                 'status' => 'draft', // Create as draft initially
                    //                 'job_id' => $salesOrder->job_id,
                    //                 'company_id' => $salesOrder->company_id,
                    //                 'created_by_user_id' => auth()->id(),
                    //             ]);

                    //             foreach ($items as $item) {
                    //                 DeliveryDocumentItem::create([
                    //                     'delivery_document_id' => $delivery->id,
                    //                     'sales_order_item_id' => $item['sales_order_item_id'],
                    //                     'product_id' => $item['product_id'],
                    //                     'unit_id' => $item['unit_id'],
                    //                     'quantity' => $item['quantity'],
                    //                     'description' => $item['description'],
                    //                 ]);
                    //             }

                    //             $delivery->is_locked = true;
                    //             $delivery->save();

                    //             $salesOrder->refreshDeliveryTracking();

                    //             return redirect(SalesDeliveryResource::getUrl('edit', ['record' => $delivery]));
                    //         });
                    //     }),
                    Action::make('createInvoice')
                        ->label('Buat Invoice')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->visible(function (SalesOrder $record): bool {
                            if (!in_array($record->order_type, ['standar', 'deposit'])) {
                                return false;
                            }
                            $meta = $record->invoice_meta ?: $record->computeInvoiceMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0 && $record->status === 'posted';
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Invoice')
                        ->modalDescription('Apakah Anda yakin ingin membuat invoice? Invoice akan dibuat dengan status terkunci.')
                        ->modalSubmitActionLabel('Ya, Buat')
                        ->action(function (SalesOrder $record) {
                            $salesOrder = SalesOrder::query()
                                ->with(['items'])
                                ->findOrFail($record->id);

                            $items = [];
                            $subtotal = 0;
                            foreach ($salesOrder->items as $soItem) {
                                $remaining = max(0.0, (float) ($soItem->quantity ?? 0) - (float) ($soItem->invoiced_quantity ?? 0));
                                if ($remaining <= 0) {
                                    continue;
                                }
                                $lineTotal = $remaining * $soItem->unit_price;
                                $subtotal += $lineTotal;
                                
                                $items[] = [
                                    'sales_order_item_id' => $soItem->id,
                                    'product_id' => $soItem->product_id,
                                    'item_name' => $soItem->item_name ?? $soItem->product?->name,
                                    'unit_id' => $soItem->unit_id,
                                    'quantity' => $remaining,
                                    'unit_price' => $soItem->unit_price,
                                    'tax_id' => $soItem->tax_id,
                                    'description' => $soItem->description,
                                    'total' => $lineTotal,
                                ];
                            }

                            // Calculate totals from sales order proportionally
                            $originalSubtotal = $salesOrder->subtotal;
                            $ratio = $originalSubtotal > 0 ? $subtotal / $originalSubtotal : 1;
                            
                            $discount = $salesOrder->discount * $ratio;
                            $otherCharges = $salesOrder->other_charges * $ratio;
                            $taxAmount = $salesOrder->tax_amount * $ratio;
                            $totalAmount = $subtotal - $discount + $otherCharges + $taxAmount;

                            return DB::transaction(function () use ($salesOrder, $items, $subtotal, $discount, $otherCharges, $taxAmount, $totalAmount) {
                                $invoice = \App\Models\SalesInvoice::create([
                                    'date' => now(),
                                    'customer_id' => $salesOrder->customer_id,
                                    'sales_order_id' => $salesOrder->id,
                                    'is_locked' => false,
                                    'status' => 'draft', // Create as draft initially
                                    'job_id' => $salesOrder->job_id,
                                    'company_id' => $salesOrder->company_id,
                                    'created_by_user_id' => auth()->id(),
                                    'subtotal' => $subtotal,
                                    'discount' => $discount,
                                    'other_charges' => $otherCharges,
                                    'tax_amount' => $taxAmount,
                                    'total_amount' => $totalAmount,
                                    'paid_amount' => 0,
                                    'outstanding_amount' => $totalAmount,
                                ]);

                                foreach ($items as $item) {
                                    \App\Models\SalesInvoiceItem::create([
                                        'sales_invoice_id' => $invoice->id,
                                        'sales_order_item_id' => $item['sales_order_item_id'],
                                        'product_id' => $item['product_id'],
                                        'item_name' => $item['item_name'],
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

                                $salesOrder->refreshInvoiceTracking();

                                return redirect(\App\Filament\Resources\SalesInvoices\SalesInvoiceResource::getUrl('edit', ['record' => $invoice]));
                            });
                        }),
                    Action::make('createPurchaseOrder')
                        ->label('Buat PO')
                        ->icon('heroicon-o-shopping-cart')
                        ->color('warning')
                        ->visible(function (SalesOrder $record): bool {
                            if (!in_array($record->order_type, ['standar', 'aktual'])) {
                                return false;
                            }
                            return $record->status === 'posted' && !$record->purchaseOrders()->exists();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Purchase Order')
                        ->modalDescription('Silakan pilih supplier untuk purchase order ini.')
                        ->modalSubmitActionLabel('Buat PO')
                        ->form([
                            Select::make('supplier_id')
                                ->label('Supplier')
                                ->options(function (SalesOrder $record) {
                                    $companyId = $record->company_id;
                                    $query = \App\Models\Contact::query()->where('is_supplier', true);
                                    if ($companyId) {
                                        $query->where('company_id', $companyId);
                                    } else {
                                        $query->whereNull('company_id');
                                    }
                                    return $query->orderBy('name')->pluck('name', 'id');
                                })
                                ->getOptionLabelUsing(fn ($value): ?string => \App\Models\Contact::find($value)?->name)
                                ->searchable(['name', 'contact_code'])
                                ->preload()
                                ->required()
                                ->rules(['required']),
                        ])
                        ->action(function (SalesOrder $record, array $data) {
                            $salesOrder = SalesOrder::query()
                                ->with(['items.product'])
                                ->findOrFail($record->id);

                            $items = [];
                            $subtotal = 0;
                            foreach ($salesOrder->items as $soItem) {
                                $items[] = [
                                    'product_id' => $soItem->product_id,
                                    'item_name' => $soItem->item_name ?? $soItem->product?->name,
                                    'quantity' => $soItem->quantity,
                                    'unit_price' => $soItem->product->cost_price ?? $soItem->unit_price, // Use cost price if available, otherwise use unit price
                                    'unit_id' => $soItem->unit_id,
                                    'tax_id' => $soItem->tax_id,
                                    'description' => $soItem->description,
                                    'total' => ($soItem->quantity * ($soItem->product->cost_price ?? $soItem->unit_price)),
                                ];

                                $subtotal += ($soItem->quantity * ($soItem->product->cost_price ?? $soItem->unit_price));
                            }

                            // Calculate totals based on the sales order
                            $discount = $salesOrder->discount; // Keep the same discount calculation approach
                            $otherCharges = $salesOrder->other_charges;
                            $taxAmount = $salesOrder->tax_amount;
                            $totalAmount = $subtotal - $discount + $otherCharges + $taxAmount;

                            return DB::transaction(function () use ($salesOrder, $items, $data, $subtotal, $discount, $otherCharges, $taxAmount, $totalAmount) {
                                $purchaseOrder = \App\Models\PurchaseOrder::create([
                                    'date' => now(),
                                    'supplier_id' => $data['supplier_id'], // Use selected supplier
                                    'sales_order_id' => $salesOrder->id, // Link to the sales order
                                    'is_locked' => false,
                                    'status' => 'draft', // Create as draft initially
                                    'job_id' => $salesOrder->job_id,
                                    'company_id' => $salesOrder->company_id,
                                    'created_by_user_id' => auth()->id(),
                                    'subtotal' => $subtotal,
                                    'discount' => $discount,
                                    'other_charges' => $otherCharges,
                                    'tax_amount' => $taxAmount,
                                    'total_amount' => $totalAmount,
                                    'purchase_order_no' => '', // Will be auto-generated
                                ]);

                                foreach ($items as $item) {
                                    \App\Models\PurchaseOrderItem::create([
                                        'purchase_order_id' => $purchaseOrder->id,
                                        'product_id' => $item['product_id'],
                                        'item_name' => $item['item_name'],
                                        'quantity' => $item['quantity'],
                                        'unit_price' => $item['unit_price'],
                                        'unit_id' => $item['unit_id'],
                                        'tax_id' => $item['tax_id'],
                                        'description' => $item['description'],
                                        'total' => $item['total'],
                                    ]);
                                }

                                return redirect(\App\Filament\Resources\PurchaseOrders\PurchaseOrderResource::getUrl('edit', ['record' => $purchaseOrder]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => SalesOrderResource::getUrl('view', ['record' => $record])),
                    Action::make('view_detail')
                        ->label('View Detail')
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->color('info')
                        ->url(fn ($record) => SalesOrderResource::getUrl('view-detail', ['record' => $record])),
                    EditAction::make(),
                    RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                    DeleteAction::make()
                        ->visible(fn (): bool => !SalesOrderResource::isLocked()),
                ]),
            ])
            ->toolbarActions([
                \App\Filament\Actions\ImportSalesOrderWithItemsAction::make()
                    ->visible(fn (): bool => !SalesOrderResource::isLocked()),
                \App\Filament\Actions\ExportSalesOrderWithItemsAction::make(),
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
                        ->deselectRecordsAfterCompletion()
                        ->visible(fn (): bool => !SalesOrderResource::isLocked()),
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => !SalesOrderResource::isLocked()),
                ])->visible(fn (): bool => !SalesOrderResource::isLocked()),
            ]);
    }
}