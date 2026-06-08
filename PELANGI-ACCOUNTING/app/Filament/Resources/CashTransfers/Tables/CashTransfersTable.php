<?php

namespace App\Filament\Resources\CashTransfers\Tables;

use App\Filament\Actions\ViewJournalVoucherAction;
use App\Models\Account;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CashTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("transfer_number")
                    ->label(__("Transfer Code"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")->date()->sortable()->label(__("Date")),
                TextColumn::make("reference_no")
                    ->searchable()
                    ->label(__("Reference No")),
                TextColumn::make("fromAccount.name")
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->fromAccount ? "{$record->fromAccount->code} - {$record->fromAccount->name}" : '-')
                    ->label(__("From Account")),
                TextColumn::make("toAccount.name")
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->toAccount ? "{$record->toAccount->code} - {$record->toAccount->name}" : '-')
                    ->label(__("To Account")),
                TextColumn::make("amount")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Amount")),
                TextColumn::make("status")
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => \Illuminate\Support\Str::headline($state))
                    ->color(
                        fn(string $state): string => match ($state) {
                            "draft" => "gray",
                            "posted" => "success",
                            default => "gray",
                        },
                    )
                    ->label(__("Status")),

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
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from')->label(__('From')),
                        DatePicker::make('until')->label(__('Until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $from) => $q->whereDate('date', '>=', $from))
                            ->when($data['until'] ?? null, fn($q, $until) => $q->whereDate('date', '<=', $until));
                    }),
                Filter::make('from_account_id')
                    ->form([
                        Select::make('from_account_id')
                            ->label(__('From Account'))
                            ->options(fn() => Account::where('is_header', false)
                                ->where('is_active', true)
                                ->where('is_cash_bank', true)
                                ->orderBy('code')
                                ->get()
                                ->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]))
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(fn($query, array $data) => $query->when($data['from_account_id'] ?? null, fn($q, $id) => $q->where('from_account_id', $id))),
                Filter::make('to_account_id')
                    ->form([
                        Select::make('to_account_id')
                            ->label(__('To Account'))
                            ->options(fn() => Account::where('is_header', false)
                                ->where('is_active', true)
                                ->where('is_cash_bank', true)
                                ->orderBy('code')
                                ->get()
                                ->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]))
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(fn($query, array $data) => $query->when($data['to_account_id'] ?? null, fn($q, $id) => $q->where('to_account_id', $id))),
                TrashedFilter::make(),
            ])
            ->defaultSort('date', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewJournalVoucherAction::make(),
                    EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),

                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
