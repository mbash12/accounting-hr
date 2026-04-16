<?php

namespace App\Filament\Resources\FaqCategories\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Kategori"))
                    ->searchable(),
                TextColumn::make("sort_order")
                    ->label(__("Urutan"))
                    ->sortable(),
                TextColumn::make("faqs_count")
                    ->counts('faqs')
                    ->label(__("Jumlah FAQ")),
                TextColumn::make("created_at")
                    ->label(__("Dibuat At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
