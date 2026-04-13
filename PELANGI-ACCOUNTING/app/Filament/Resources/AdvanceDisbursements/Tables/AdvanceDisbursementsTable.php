<?php

namespace App\Filament\Resources\AdvanceDisbursements\Tables;

use App\Filament\Actions\ViewJournalVoucherAction;
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

class AdvanceDisbursementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("advance_number")
                    ->label(__("Advance No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("recipient.name")
                    ->label(__("Recipient"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("fromAccount.account_name")
                    ->label(__("From Account"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total")
                    ->label(__("Total"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make("created_at")
                    ->label(__("Created at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Updated at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Deleted at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([TrashedFilter::make()])
            ->recordActions([
                ActionGroup::make([
                    ViewJournalVoucherAction::make(),
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
