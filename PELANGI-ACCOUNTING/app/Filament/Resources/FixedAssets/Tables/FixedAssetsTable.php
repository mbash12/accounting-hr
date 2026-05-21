<?php

namespace App\Filament\Resources\FixedAssets\Tables;

use App\Filament\Actions\ExportFixedAssetAction;
use App\Filament\Actions\ImportFixedAssetAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FixedAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Asset Name"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("code")
                    ->searchable()
                    ->copyable()
                    ->label(__("Asset Code")),
                TextColumn::make("category.name")
                    ->label(__("Category"))
                    ->searchable(),
                TextColumn::make("book_value")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Book Value")),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("location")
                    ->label(__("Location"))
                    ->searchable()
                    ->placeholder(__("N/A"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("acquisition_date")
                    ->date()
                    ->sortable()
                    ->label(__("Acquired"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("acquisition_value")
                    ->money("IDR")
                    ->sortable()
                    ->label(__("Acquisition Value"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("department.name")
                    ->label(__("Department"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("useful_life")
                    ->label(__("Useful Life"))
                    ->numeric()
                    ->sortable()
                    ->suffix(" " . __("years"))
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
                ImportFixedAssetAction::make(),
                ExportFixedAssetAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
