<?php

namespace App\Filament\Resources\THRCalculations\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
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
                    ->label(__('Employee'))
                    ->relationship('employee', 'name')
                    ->required()
                    ->disabled(),
                TextInput::make('basic_salary')
                    ->label(__('Basic Salary'))
                    ->numeric()
                    ->disabled(),
                TextInput::make('months_service')
                    ->label(__('Service Period (Months)'))
                    ->numeric()
                    ->disabled(),
                TextInput::make('amount')
                    ->label(__('THR Amount'))
                    ->numeric()
                    ->required(),
                TextInput::make('pph21')
                    ->label(__('PPh21'))
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.name')
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Employee'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('months_service')
                    ->label(__('Service Period (Months)'))
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('THR Amount'))
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
                // THR is usually generated automatically, but maybe allow adding manually
            ])
            ->recordActions([])
            ->bulkActions([]);
    }
}
