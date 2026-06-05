<?php

namespace App\Filament\Resources\BankAccounts\Tables;

use App\Filament\Actions\ExportBankAccountsAction;
use App\Filament\Actions\ImportBankAccountsAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BankAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank.name')
                    ->label(__('Bank'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('account_number')
                    ->label(__('Account Number'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('account_name')
                    ->label(__('Account Name'))
                    ->searchable(),
                TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('account_number')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                ImportBankAccountsAction::make(),
                ExportBankAccountsAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
