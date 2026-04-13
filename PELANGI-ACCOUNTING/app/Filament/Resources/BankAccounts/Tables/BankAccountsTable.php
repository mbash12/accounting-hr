<?php

namespace App\Filament\Resources\BankAccounts\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BankAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("account_number")
                    ->searchable()
                    ->copyable()
                    ->weight("bold")
                    ->label(__("Account #")),
                TextColumn::make("account_name")
                    ->searchable()
                    ->label(__("Account Name")),
                TextColumn::make("account_type")
                    ->label(__("Account Type"))
                    ->searchable()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "checking" => __("Checking"),
                            "savings" => __("Savings"),
                            "credit_card" => __("Credit Card"),
                            "investment" => __("Investment"),
                            default => $state,
                        },
                    ),
                TextColumn::make("balance")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Balance")),
                TextColumn::make("bank.name")->searchable()->label(__("Bank")),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
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
            ])
            ->filters([TrashedFilter::make()])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
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
