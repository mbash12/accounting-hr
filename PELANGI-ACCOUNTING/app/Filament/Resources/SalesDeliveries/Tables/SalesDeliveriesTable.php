<?php

namespace App\Filament\Resources\SalesDeliveries\Tables;

use App\Filament\Actions\ImportSalesDeliveryWithItemsAction;
use App\Filament\Actions\ExportSalesDeliveryWithItemsAction;
use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use App\Filament\Resources\SalesReturns\SalesReturnResource;
use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
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

class SalesDeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("delivery_number")
                    ->label(__("Delivery No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("customer.name")
                    ->label(__("Customer"))
                    ->searchable()
                    ->sortable(),
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
                TextColumn::make("salesOrder.order_number")
                    ->label(__("Sales Order No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("job.title")
                    ->label(__("Project"))
                    ->searchable()
                    ->sortable()
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
                Filter::make('sales_order')
                    ->form([
                        \Filament\Forms\Components\Select::make('sales_order_id')
                            ->label('Sales Order No.')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\SalesOrder::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query->limit(50)->pluck('order_number', 'id');
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\SalesOrder::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query
                                    ->whereRaw('LOWER(order_number) LIKE ?', ['%' . strtolower($search) . '%'])
                                    ->limit(50)
                                    ->pluck('order_number', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\SalesOrder::find($value)?->order_number),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['sales_order_id'],
                                fn (Builder $query, $salesOrderId): Builder => $query->where('sales_order_id', $salesOrderId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['sales_order_id']) {
                            return null;
                        }
                        $salesOrder = \App\Models\SalesOrder::find($data['sales_order_id']);
                        return 'SO: ' . ($salesOrder?->order_number ?? $data['sales_order_id']);
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
                        ->visible(function (DeliveryDocument $record): bool {
                            if (!$record->is_locked || $record->status !== 'posted') return false;
                            $meta = $record->return_meta ?: $record->computeReturnMeta();
                            return (float) ($meta['remaining'] ?? 0) > 0;
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Create Sales Return')
                        ->modalDescription('Are you sure you want to create a sales return? The document will be created in locked status.')
                        ->modalSubmitActionLabel('Yes, Create')
                        ->action(function (DeliveryDocument $record) {
                            $delivery = DeliveryDocument::query()
                                ->with(['items.product'])
                                ->findOrFail($record->id);

                            $items = [];
                            foreach ($delivery->items as $item) {
                                $qty = (float) ($item->quantity ?? 0);
                                $returned = (float) ($item->returned_quantity ?? 0);
                                $remaining = max(0.0, $qty - $returned);
                                if ($remaining <= 0) continue;
                                
                                $items[] = [
                                    'delivery_document_item_id' => $item->id,
                                    'product_id' => $item->product_id,
                                    'unit_id' => $item->unit_id,
                                    'quantity' => $remaining,
                                    'description' => $item->description,
                                    'conversion_factor' => $item->conversion_factor ?? 1,
                                    'base_quantity' => $item->base_quantity ?? $remaining,
                                ];
                            }

                            return DB::transaction(function () use ($delivery, $items) {
                                $return = SalesReturn::create([
                                    'date' => now(),
                                    'delivery_document_id' => $delivery->id,
                                    'customer_id' => $delivery->customer_id,
                                    'company_id' => $delivery->company_id,
                                    'job_id' => $delivery->job_id,
                                    'status' => 'draft', // Create as draft initially
                                    'is_locked' => false,
                                ]);

                                foreach ($items as $item) {
                                    SalesReturnItem::create([
                                        'sales_return_id' => $return->id,
                                        'delivery_document_item_id' => $item['delivery_document_item_id'],
                                        'product_id' => $item['product_id'],
                                        'unit_id' => $item['unit_id'],
                                        'quantity' => $item['quantity'],
                                        'description' => $item['description'],
                                        'return_reason' => 'Automatic return from delivery',
                                        'conversion_factor' => $item['conversion_factor'],
                                        'base_quantity' => $item['base_quantity'],
                                    ]);
                                }

                                $return->is_locked = true;
                                $return->save();

                                $delivery->refreshReturnTracking();

                                return redirect(SalesReturnResource::getUrl('edit', ['record' => $return]));
                            });
                        }),
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => SalesDeliveryResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                    \App\Filament\Actions\RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                ]),
            ])
            ->toolbarActions([
                \App\Filament\Actions\ImportSalesDeliveryWithItemsAction::make(),
                \App\Filament\Actions\ExportSalesDeliveryWithItemsAction::make(),
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
