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
                    ->label(__("Nama Kategori"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("code")
                    ->label(__("Kode Kategori"))
                    ->searchable()
                    ->weight("bold"),
                TextColumn::make("depreciation_method")
                    ->label(__("Metode Penyusutan"))
                    ->searchable()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            "straight_line" => __("Garis Lurus"),
                            "declining_balance" => __("Saldo Menurun"),
                            "double_declining" => __("Saldo Menurun Ganda"),
                            "sum_of_years" => __("Jumlah Angka Tahun"),
                            "units_of_production" => __("Satuan Hasil Produksi"),
                            default => $state,
                        },
                    ),
                TextColumn::make("useful_life")
                    ->label(__("Masa Manfaat"))
                    ->numeric()
                    ->sortable()
                    ->suffix(" " . __("tahun")),

                IconColumn::make("is_active")->boolean()->label(__("Aktif")),
                TextColumn::make("assetAccount.name")
                    ->label(__("Akun Aset"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("depreciationAccount.name")
                    ->label(__("Akun Penyusutan"))
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
