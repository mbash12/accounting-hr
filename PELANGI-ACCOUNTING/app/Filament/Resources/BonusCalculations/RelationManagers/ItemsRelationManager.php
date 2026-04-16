<?php

namespace App\Filament\Resources\BonusCalculations\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label(__('Karyawan'))
                    ->relationship('employee', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable()
                    ->required(),
                TextInput::make('amount')
                    ->label(__('Jumlah Bonus'))
                    ->numeric()
                    ->required(),
                TextInput::make('pph21')
                    ->label(__('PPh21'))
                    ->numeric()
                    ->disabled()
                    ->placeholder(__('Akan dihitung otomatis')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.name')
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Karyawan'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('Jumlah Bonus'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('pph21')
                    ->label(__('PPh21'))
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
