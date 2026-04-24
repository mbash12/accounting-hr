<?php

namespace App\Filament\Resources\AttendanceSpots\Tables;

use Filament\Actions\ActionGroup;
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

class AttendanceSpotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nama Spot'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('latitude')
                    ->label(__('Latitude'))
                    ->sortable(),
                TextColumn::make('longitude')
                    ->label(__('Longitude'))
                    ->sortable(),
                TextColumn::make('radius_meters')
                    ->label(__('Radius (m)'))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('Aktif'))
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
