<?php

namespace App\Filament\Resources\Banks\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

use Filament\Tables\Table;
use App\Filament\Actions\ExportBanksAction;
use App\Filament\Actions\ImportBanksAction;

class BanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("logo")
                    ->label(__("Logo"))
                    ->circular()
                    ->disk("public"),
                TextColumn::make("name")->label(__("Bank Name"))->searchable(),
                TextColumn::make("code")
                    ->label(__("Bank Code"))
                    ->searchable()
                    ->weight("bold"),

                TextColumn::make("country")->label(__("Country"))->searchable(),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("clearing_code")
                    ->label(__("Clearing Code"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("skn_code")
                    ->label(__("SKN Code"))
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
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                ImportBanksAction::make('import'),
                ExportBanksAction::make('export'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
