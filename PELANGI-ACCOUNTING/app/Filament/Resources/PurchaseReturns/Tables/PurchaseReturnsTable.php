<?php

namespace App\Filament\Resources\PurchaseReturns\Tables;

use App\Filament\Actions\ImportPurchaseReturnWithItemsAction;
use App\Filament\Actions\ExportPurchaseReturnWithItemsAction;
use App\Filament\Resources\PurchaseReturns\PurchaseReturnResource;
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

class PurchaseReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("return_number")
                    ->label("Return No.")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label("Date")
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("supplier.name")
                    ->label("Supplier")
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
                TextColumn::make("goodsReceipt.receipt_number")
                    ->label("Receipt No.")
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("goodsReceipt.purchaseOrder.purchase_order_no")
                    ->label(__("Purchase Order No."))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("job.title")
                    ->label("Project")
                    ->searchable()
                    ->sortable()
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
                Filter::make('goods_receipt')
                    ->form([
                        \Filament\Forms\Components\Select::make('goods_receipt_id')
                            ->label('Receipt No.')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\GoodsReceipt::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query->limit(50)->pluck('receipt_number', 'id');
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\GoodsReceipt::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query
                                    ->whereRaw('LOWER(receipt_number) LIKE ?', ['%' . strtolower($search) . '%'])
                                    ->limit(50)
                                    ->pluck('receipt_number', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\GoodsReceipt::find($value)?->receipt_number),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['goods_receipt_id'],
                                fn (Builder $query, $goodsReceiptId): Builder => $query->where('goods_receipt_id', $goodsReceiptId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['goods_receipt_id']) {
                            return null;
                        }
                        $goodsReceipt = \App\Models\GoodsReceipt::find($data['goods_receipt_id']);
                        return 'GR: ' . ($goodsReceipt?->receipt_number ?? $data['goods_receipt_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => PurchaseReturnResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                    \App\Filament\Actions\RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                ]),
            ])
            ->toolbarActions([
                ImportPurchaseReturnWithItemsAction::make(),
                ExportPurchaseReturnWithItemsAction::make(),
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
                            $records->each(fn ($record) => $record->update([
                                'status' => $data['status'],
                                'is_locked' => $data['status'] !== 'draft' ?: $record->is_locked,
                            ]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
