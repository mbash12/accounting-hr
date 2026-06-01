<?php

namespace App\Filament\Resources\DeferredRevenues\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeferredRevenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract_number')
                    ->label(__('Contract No.'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label(__('Date')),
                TextColumn::make('customer_name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->placeholder(__('N/A')),
                TextColumn::make('total_amount')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable()
                    ->label(__('Total Amount')),
                TextColumn::make('recognized_amount')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable()
                    ->label(__('Recognized')),
                TextColumn::make('remaining_amount')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable()
                    ->label(__('Remaining')),
                TextColumn::make('progress')
                    ->label(__('Progress'))
                    ->formatStateUsing(function ($record) {
                        if ($record->total_amount <= 0) return '0%';
                        $pct = round(($record->recognized_amount / $record->total_amount) * 100, 1);
                        return $pct . '%';
                    })
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->status === 'completed' => 'success',
                        $record->recognized_amount > 0 => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->label(__('Status')),
                TextColumn::make('period_start')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label(__('Period Start'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('period_end')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label(__('Period End'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_periods')
                    ->numeric()
                    ->sortable()
                    ->label(__('Periods'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdByUser.name')
                    ->label(__('Created By'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label(__('Created At'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
