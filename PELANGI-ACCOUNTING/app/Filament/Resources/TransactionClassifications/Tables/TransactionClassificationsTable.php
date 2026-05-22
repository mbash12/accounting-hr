<?php

namespace App\Filament\Resources\TransactionClassifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TransactionClassificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("code")
                    ->label(__("Code"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("name")
                    ->label(__("Name"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("classification_type")
                    ->label(__("Classification Type"))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'operating' => __('Operating'),
                        'investing' => __('Investing'),
                        'financing' => __('Financing'),
                        'non_operating' => __('Non Operating'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'operating' => 'success',
                        'investing' => 'info',
                        'financing' => 'warning',
                        'non_operating' => 'gray',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make("tax_impact")
                    ->label(__("Tax Impact"))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? match ($state) {
                        'taxable' => __('Taxable'),
                        'exempt' => __('Exempt'),
                        'zero_rated' => __('Zero Rated'),
                        'out_of_scope' => __('Out of Scope'),
                        default => $state,
                    } : '-')
                    ->color(fn (?string $state): string => $state ? match ($state) {
                        'taxable' => 'danger',
                        'exempt' => 'success',
                        'zero_rated' => 'info',
                        'out_of_scope' => 'gray',
                        default => 'gray',
                    } : 'gray')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("defaultAccount.code")
                    ->label(__("Default Account"))
                    ->formatStateUsing(function ($record) {
                        if ($record->defaultAccount) {
                            return "{$record->defaultAccount->code} - {$record->defaultAccount->name}";
                        }
                        return '-';
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make("company.name")
                    ->label(__("Company"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make("is_active")
                    ->boolean()
                    ->label(__("Active"))
                    ->sortable(),
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
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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



