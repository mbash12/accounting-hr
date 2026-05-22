<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Actions\ImportProductsAction;
use App\Filament\Actions\ExportProductsAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("image")
                    ->label(__("Image"))
                    ->disk("public"),
                TextColumn::make("name")
                    ->label(__("Name"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("code")
                    ->searchable()
                    ->copyable()
                    ->label(__("Product Code")),
                TextColumn::make("productGroup.name")
                    ->label(__("Group"))
                    ->searchable(),
                TextColumn::make("unit.name")->label(__("Unit"))->searchable(),
                TextColumn::make("selling_price")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Price")),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("cost_price")
                    ->money("USD")
                    ->sortable()
                    ->label(__("Cost"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("reorder_level")
                    ->numeric()
                    ->sortable()
                    ->label(__("Reorder"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("max_stock")
                    ->numeric()
                    ->sortable()
                    ->label(__("Max Stock"))
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
                TextColumn::make("product_type")
                    ->label(__("Product Type"))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'service' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                ImportProductsAction::make(),
                ExportProductsAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
