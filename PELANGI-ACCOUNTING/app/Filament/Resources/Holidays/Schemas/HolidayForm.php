<?php

namespace App\Filament\Resources\Holidays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Holiday Details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(200),
                        DatePicker::make('date')
                            ->label(__('Date'))
                            ->required(),
                        Toggle::make('is_cuti_bersama')
                            ->label(__('Collective Leave'))
                            ->default(false),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
