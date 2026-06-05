<?php

namespace App\Filament\Resources\SalesOrders\Tables;

use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Models\SalesOrder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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
                    ->label("Order No."),
                TextColumn::make("date")
                    ->date()
                    ->sortable()
                    ->label("Order Date"),
                TextColumn::make("customer.name")
                    ->searchable()
                    ->label("Customer"),
                TextColumn::make("products_list")
                    ->label("Products")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->whereHas('items', function ($q2) use ($search) {
                                $q2->whereHas('product', function ($q3) use ($search) {
                                    $q3->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                                });
                            });
                        });
                    })
                    ->getStateUsing(function ($record): string {
                        $names = $record->items
                            ->map(fn ($item) => $item->product?->name)
                            ->filter(fn ($name) => $name !== null && $name !== '')
                            ->unique()
                            ->values();

                        return $names->isNotEmpty() ? $names->implode(', ') : '-';
                    })
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make("total_amount")
                    ->money("IDR")
                    ->sortable()
                    ->label("Total"),

                TextColumn::make("createdByUser.name")
                    ->label("Created By")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label("Updated At")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label("Deleted At")
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
                Filter::make('customer')
                    ->form([
                        \Filament\Forms\Components\Select::make('customer_id')
                            ->label('Customer')
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
                        return 'Customer: ' . ($customer?->name ?? $data['customer_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('createInvoice')
                        ->label('Create Invoice')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->visible(function (SalesOrder $record): bool {
                            $meta = $record->invoice_meta ?: $record->computeInvoiceMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0;
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Create Invoice')
                        ->modalDescription('Are you sure you want to create an invoice? It will be created as locked.')
                        ->modalSubmitActionLabel('Yes, Create')
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
                                    'unit_id' => $soItem->unit_id,
                                    'quantity' => $remaining,
                                    'unit_price' => $soItem->unit_price,
                                    'tax_id' => $soItem->tax_id,
                                    'description' => $soItem->description,
                                    'total' => $lineTotal,
                                    'conversion_factor' => $soItem->conversion_factor ?? 1,
                                    'base_quantity' => $soItem->base_quantity ?? $remaining,
                                ];
                            }

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
                                    'status' => 'draft',
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

                                $salesOrder->refreshInvoiceTracking();

                                return redirect(\App\Filament\Resources\SalesInvoices\SalesInvoiceResource::getUrl('edit', ['record' => $invoice]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => SalesOrderResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    RegenerateJournalEntry::make('regenerateJournalEntry'),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                \App\Filament\Actions\ImportSalesOrderWithItemsAction::make(),
                \App\Filament\Actions\ExportSalesOrderWithItemsAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
