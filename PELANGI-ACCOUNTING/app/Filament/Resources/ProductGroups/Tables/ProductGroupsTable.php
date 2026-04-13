<?php

namespace App\Filament\Resources\ProductGroups\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use App\Filament\Actions\ExportProductGroupsAction;
use App\Filament\Actions\ImportProductGroupsAction;

class ProductGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Product Group Name"))
                    ->searchable(),
                TextColumn::make("code")
                    ->label(__("Code"))
                    ->searchable()
                    ->copyable(),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("shipping_type")
                    ->label(__("Jenis Produk"))
                    ->searchable()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "physical" => __("Produk Fisik"),
                            "digital" => __("Produk Digital"),
                            default => $state,
                        },
                    )
                    ->toggleable(),
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
                ImportProductGroupsAction::make(),
                ExportProductGroupsAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
