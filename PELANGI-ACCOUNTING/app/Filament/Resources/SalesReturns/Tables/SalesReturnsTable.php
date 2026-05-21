<?php

namespace App\Filament\Resources\SalesReturns\Tables;

use App\Filament\Actions\ImportSalesReturnWithItemsAction;
use App\Filament\Actions\ExportSalesReturnWithItemsAction;
use App\Filament\Resources\SalesReturns\SalesReturnResource;
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

class SalesReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("return_number")
                    ->label(__("Return No."))
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
                TextColumn::make("deliveryDocument.delivery_number")
                    ->label(__("Delivery No."))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("deliveryDocument.salesOrder.order_number")
                    ->label(__("Sales Order No."))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
                Filter::make('delivery_document')
                    ->form([
                        \Filament\Forms\Components\Select::make('delivery_document_id')
                            ->label('Delivery No.')
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\DeliveryDocument::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query->limit(50)->pluck('delivery_number', 'id');
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\DeliveryDocument::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                          ->orWhereNull('company_id');
                                    });
                                }
                                return $query
                                    ->whereRaw('LOWER(delivery_number) LIKE ?', ['%' . strtolower($search) . '%'])
                                    ->limit(50)
                                    ->pluck('delivery_number', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\DeliveryDocument::find($value)?->delivery_number),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['delivery_document_id'],
                                fn (Builder $query, $deliveryDocumentId): Builder => $query->where('delivery_document_id', $deliveryDocumentId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['delivery_document_id']) {
                            return null;
                        }
                        $deliveryDocument = \App\Models\DeliveryDocument::find($data['delivery_document_id']);
                        return 'DO: ' . ($deliveryDocument?->delivery_number ?? $data['delivery_document_id']);
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->defaultSort('id', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => SalesReturnResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                    \App\Filament\Actions\RegenerateJournalEntry::make('regenerateJournalEntry')
                        ->visible(fn ($record) => $record->status !== 'draft'),
                ]),
            ])
            ->toolbarActions([
                ImportSalesReturnWithItemsAction::make(),
                ExportSalesReturnWithItemsAction::make(),
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
