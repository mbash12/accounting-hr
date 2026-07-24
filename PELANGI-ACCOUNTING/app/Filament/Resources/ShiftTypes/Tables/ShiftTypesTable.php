<?php

namespace App\Filament\Resources\ShiftTypes\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->is_off ? 'danger' : 'info'),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label(__('Start'))
                    ->time('H:i')
                    ->placeholder('—'),
                TextColumn::make('end_time')
                    ->label(__('End'))
                    ->time('H:i')
                    ->placeholder('—'),
                ColorColumn::make('color')->label(__('BG')),
                ColorColumn::make('text_color')->label(__('Text')),
                IconColumn::make('is_off')->label(__('Off'))->boolean(),
                IconColumn::make('is_active')->label(__('Active'))->boolean(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
