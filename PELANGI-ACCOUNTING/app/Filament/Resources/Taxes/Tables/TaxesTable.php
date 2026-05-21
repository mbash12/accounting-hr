<?php

namespace App\Filament\Resources\Taxes\Tables;

use App\Filament\Actions\ExportTaxesAction;
use App\Filament\Actions\ImportTaxesAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")->label(__("Tax Name"))->searchable(),
                TextColumn::make("code")->label(__("Tax Code"))->searchable(),
                TextColumn::make("tax_percentage")
                    ->label(__("Tax Percentage"))
                    ->numeric()
                    ->sortable()
                    ->suffix("%"),
                // TextColumn::make("tax_type")
                //     ->label(__("Jenis Pajak"))
                //     ->searchable(),

                IconColumn::make("is_active")->boolean()->label(__("Active")),
                TextColumn::make("effective_date")
                    ->label(__("Effective Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("expiry_date")
                    ->label(__("Expiry Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make("is_purchase_tax")
                    ->boolean()
                    ->label(__("Purchase Tax"))
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make("is_sales_tax")
                    ->boolean()
                    ->label(__("Sales Tax"))
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make("compound_tax")
                    ->boolean()
                    ->label(__("Compound Tax"))
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
                ImportTaxesAction::make(),
                ExportTaxesAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
