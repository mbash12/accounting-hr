<?php

namespace App\Filament\Resources\PurchaseInvoices\Tables;

use App\Filament\Actions\ImportPurchaseInvoiceWithItemsAction;
use App\Filament\Actions\ExportPurchaseInvoiceWithItemsAction;
use App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource;
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

class PurchaseInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("invoice_number")
                    ->label(__("No. Faktur"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Tanggal"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("due_date")
                    ->label(__("Jatuh Tempo"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("paymentTerm.name")
                    ->label(__("Termin"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("supplier.name")
                    ->label(__("Pemasok"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total")
                    ->label(__("Total"))
                    ->numeric()
                    ->money("IDR")
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
                TextColumn::make("purchaseOrder.purchase_order_no")
                    ->label(__("No. Pesanan Pembelian"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("purchaseOrder.salesOrder.client_po_number")
                    ->label(__("No. PO Pelanggan"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("purchaseOrder.salesOrder.jb_job_number")
                    ->label(__("No. Job JB"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("job.title")
                    ->label(__("Proyek"))
                    ->searchable()
                    ->sortable()
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
                Filter::make('purchase_order')
                    ->form([
                        \Filament\Forms\Components\Select::make('purchase_order_id')
                            ->label('No. Pesanan Pembelian')
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
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => PurchaseInvoiceResource::getUrl('view', ['record' => $record])),
                    Action::make('print')
                        ->label('Print')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->url(fn ($record) => PurchaseInvoiceResource::getUrl('view', ['record' => $record]) . '?print=1')
                        ->openUrlInNewTab(),
                    EditAction::make(),
                    RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                    \App\Filament\Actions\ViewJournalVoucherAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                ImportPurchaseInvoiceWithItemsAction::make(),
                ExportPurchaseInvoiceWithItemsAction::make(),
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
