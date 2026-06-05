<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Filament\Actions\ImportPurchaseOrderWithItemsAction;
use App\Filament\Actions\ExportPurchaseOrderWithItemsAction;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
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
                    ->label(__("Purchase Order No.")),
                TextColumn::make("date")
                    ->date()
                    ->sortable()
                    ->label(__("Order Date")),
                TextColumn::make("supplier.name")
                    ->searchable()
                    ->label(__("Supplier")),
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
                            "approved" => __("Approved"),
                            "rejected" => __("Rejected"),
                            "posted" => __("Posted"),
                            default => $state,
                        },
                    )
                    ->color(
                        fn(string $state): string => match ($state) {
                            "draft" => "gray",
                            "approved" => "warning",
                            "rejected" => "danger",
                            "posted" => "success",
                            default => "gray",
                        },
                    ),
                TextColumn::make("createdByUser.name")
                    ->label(__("Created By"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("Created At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Updated At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Deleted At"))
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
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('To Date'),
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
                            $indicators[] = 'From: ' . $data['date_from'];
                        }
                        if ($data['date_until'] ?? null) {
                            $indicators[] = 'To: ' . $data['date_until'];
                        }
                        return $indicators;
                    }),
                Filter::make('supplier')
                    ->form([
                        \Filament\Forms\Components\Select::make('supplier_id')
                            ->label('Supplier')
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
                        return 'Supplier: ' . ($supplier?->name ?? $data['supplier_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
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
                            $result = $record->approveWithWisma();

                            if (!($result['success'] ?? false)) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Purchase Order Failed to Approve')
                                    ->body("Purchase Order {$record->purchase_order_no} gagal diapprove ke Wisma, status lokal tidak diubah.")
                                    ->send();

                                return;
                            }

                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Purchase Order Approved')
                                ->body("Purchase Order {$record->purchase_order_no} has been approved.")
                                ->send();
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft')
                        ->form([
                            Textarea::make('comment')
                                ->label('Comment')
                                ->rows(3)
                                ->placeholder('Optional rejection comment for Wisma'),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Reject Purchase Order')
                        ->modalDescription('Are you sure you want to reject this purchase order?')
                        ->modalSubmitActionLabel('Yes, Reject')
                        ->action(function (PurchaseOrder $record, array $data) {
                            $result = $record->rejectWithWisma($data['comment'] ?? null);

                            if (!($result['success'] ?? false)) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Purchase Order Failed to Reject')
                                    ->body("Purchase Order {$record->purchase_order_no} gagal direject ke Wisma, status lokal tidak diubah.")
                                    ->send();

                                return;
                            }

                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Purchase Order Rejected')
                                ->body("Purchase Order {$record->purchase_order_no} has been rejected.")
                                ->send();
                        }),
                    Action::make('createGoodsReceipt')
                        ->label('Create Goods Receipt')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->color('primary')
                        ->visible(function (PurchaseOrder $record): bool {
                            $meta = $record->receipt_meta ?: $record->computeReceiptMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0 && $record->status === 'approved';
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Create Goods Receipt')
                        ->modalDescription('Are you sure you want to create a goods receipt? The document will be created in locked status.')
                        ->modalSubmitActionLabel('Yes, Create')
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
                                    'conversion_factor' => $poItem->conversion_factor ?? 1,
                                    'base_quantity' => $poItem->base_quantity ?? $remaining,
                                ];
                            }

                            return DB::transaction(function () use ($purchaseOrder, $items) {
                                $goodsReceipt = GoodsReceipt::create([
                                    'date' => now(),
                                    'supplier_id' => $purchaseOrder->supplier_id,
                                    'purchase_order_id' => $purchaseOrder->id,
                                    'is_locked' => false,
                                    'status' => 'draft',
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
                                        'conversion_factor' => $item['conversion_factor'],
                                        'base_quantity' => $item['base_quantity'],
                                    ]);
                                }

                                $goodsReceipt->is_locked = true;
                                $goodsReceipt->save();

                                $purchaseOrder->refreshReceiptTracking();

                                return redirect()->to(GoodsReceiptResource::getUrl('edit', ['record' => $goodsReceipt]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => PurchaseOrderResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                ImportPurchaseOrderWithItemsAction::make(),
                ExportPurchaseOrderWithItemsAction::make(),
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('changeStatus')
                        ->label('Change Status')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->form([
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $validTransitions = [
                                'draft' => ['approved', 'rejected'],
                            ];
                            $targetStatus = $data['status'];
                            $updated = 0;
                            $skipped = 0;
                            $records->each(function ($record) use ($targetStatus, $validTransitions, &$updated, &$skipped) {
                                if (isset($validTransitions[$record->status]) && in_array($targetStatus, $validTransitions[$record->status])) {
                                    $record->update(['status' => $targetStatus]);
                                    $updated++;
                                } else {
                                    $skipped++;
                                }
                            });

                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Status Updated')
                                ->body("Updated: {$updated}, Skipped (invalid transition): {$skipped}")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
