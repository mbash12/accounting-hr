<?php

namespace App\Forms\Components;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerSearchTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable(),
            ])
            ->searchable(['name', 'email', 'phone', 'company']);
    }
}