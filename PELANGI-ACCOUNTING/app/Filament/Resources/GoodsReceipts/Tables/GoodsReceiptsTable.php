<?php

namespace App\Filament\Resources\GoodsReceipts\Tables;

use App\Filament\Actions\ImportGoodsReceiptWithItemsAction;
use App\Filament\Actions\ExportGoodsReceiptWithItemsAction;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use App\Filament\Resources\PurchaseReturns\PurchaseReturnResource;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GoodsReceiptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("receipt_number")
                    ->searchable()
                    ->copyable()
                    ->weight("bold")
                    ->label(__("Receipt No.")),
                TextColumn::make("date")
                    ->date()
                    ->sortable()
                    ->label(__("Date")),
                TextColumn::make("supplier.name")
                    ->searchable()
                    ->label(__("Supplier")),
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
                TextColumn::make("purchaseOrder.purchase_order_no")
                    ->label(__("Purchase Order No."))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make("reference_no")
                    ->label(__("Reference"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("items_count")
                    ->label(__("Item Count"))
                    ->getStateUsing(function ($record) {
                        return $record->items->count();
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("department.name")
                    ->label(__("Department"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Filter::make('purchase_order')
                    ->form([
                        \Filament\Forms\Components\Select::make('purchase_order_id')
                            ->label('Purchase Order No.')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\PurchaseOrder::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query->limit(50)->pluck('purchase_order_no', 'id');
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\PurchaseOrder::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query
                                    ->whereRaw('LOWER(purchase_order_no) LIKE ?', ['%' . strtolower($search) . '%'])
                                    ->limit(50)
                                    ->pluck('purchase_order_no', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\PurchaseOrder::find($value)?->purchase_order_no),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['purchase_order_id'],
                                fn (Builder $query, $purchaseOrderId): Builder => $query->where('purchase_order_id', $purchaseOrderId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['purchase_order_id']) {
                            return null;
                        }
                        $purchaseOrder = \App\Models\PurchaseOrder::find($data['purchase_order_id']);
                        return 'PO: ' . ($purchaseOrder?->purchase_order_no ?? $data['purchase_order_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('createReturn')
                        ->label('Create Return')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->visible(function (GoodsReceipt $record): bool {
                            if (!$record->is_locked || $record->status !== 'posted') return false;
                            $meta = $record->return_meta ?: $record->computeReturnMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0;
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Create Purchase Return')
                        ->modalDescription('Are you sure you want to create a purchase return? The document will be created in locked status.')
                        ->modalSubmitActionLabel('Yes, Create')
                        ->action(function (GoodsReceipt $record) {
                            $receipt = GoodsReceipt::query()
                                ->with(['items.product'])
                                ->findOrFail($record->id);

                            $items = [];
                            foreach ($receipt->items as $item) {
                                $qty = (float) ($item->quantity ?? 0);
                                $returned = (float) ($item->returned_quantity ?? 0);
                                $remaining = max(0.0, $qty - $returned);
                                if ($remaining <= 0) continue;
                                
                                $items[] = [
                                    'goods_receipt_item_id' => $item->id,
                                    'product_id' => $item->product_id,
                                    'unit_id' => $item->unit_id,
                                    'quantity' => $remaining,
                                    'description' => $item->description,
                                ];
                            }

                            return DB::transaction(function () use ($receipt, $items) {
                                $return = PurchaseReturn::create([
                                    'date' => now(),
                                    'goods_receipt_id' => $receipt->id,
                                    'supplier_id' => $receipt->supplier_id,
                                    'company_id' => $receipt->company_id,
                                    'job_id' => $receipt->job_id,
                                    'status' => 'draft', // Create as draft initially
                                    'is_locked' => false,
                                ]);

                                foreach ($items as $item) {
                                    PurchaseReturnItem::create([
                                        'purchase_return_id' => $return->id,
                                        'goods_receipt_item_id' => $item['goods_receipt_item_id'],
                                        'product_id' => $item['product_id'],
                                        'unit_id' => $item['unit_id'],
                                        'quantity' => $item['quantity'],
                                        'description' => $item['description'],
                                        'return_reason' => 'Automatic return from goods receipt',
                                    ]);
                                }

                                $return->is_locked = true;
                                $return->save();

                                $receipt->refreshReturnTracking();

                                return redirect(PurchaseReturnResource::getUrl('edit', ['record' => $return]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => GoodsReceiptResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                    \App\Filament\Actions\RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                ])
            ])
            ->toolbarActions([
                ImportGoodsReceiptWithItemsAction::make(),
                ExportGoodsReceiptWithItemsAction::make(),
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
