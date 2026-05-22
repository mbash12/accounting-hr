<?php

namespace App\Filament\Resources\FixedAssetCategories\Tables;

use App\Filament\Actions\ExportFixedAssetCategoryAction;
use App\Filament\Actions\ImportFixedAssetCategoryAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FixedAssetCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Category Name"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("code")
                    ->label(__("Category Code"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("depreciation_method")
                    ->label(__("Depreciation Method"))
                    ->searchable()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "straight_line" => __("Straight Line"),
                            "declining_balance" => __("Declining Balance"),
                            "double_declining" => __("Double Declining"),
                            "sum_of_years" => __("Sum of Years"),
                            "units_of_production" => __("Units of Production"),
                            default => $state,
                        },
                    ),
                TextColumn::make("useful_life")
                    ->label(__("Useful Life"))
                    ->numeric()
                    ->sortable()
                    ->suffix(" " . __("years")),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("assetAccount.name")
                    ->label(__("Asset Account"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("depreciationAccount.name")
                    ->label(__("Depreciation Account"))
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
                ImportFixedAssetCategoryAction::make(),
                ExportFixedAssetCategoryAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
