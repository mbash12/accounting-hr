<?php

namespace App\Filament\Resources\AttendanceSpots\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceSpotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Attendance Spot Details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Spot Name'))
                            ->required()
                            ->maxLength(200),
                        TextInput::make('latitude')
                            ->label(__('Latitude'))
                            ->required()
                            ->numeric()
                            ->step(0.000001),
                        TextInput::make('longitude')
                            ->label(__('Longitude'))
                            ->required()
                            ->numeric()
                            ->step(0.000001),
                        TextInput::make('radius_meters')
                            ->label(__('Radius (meter)'))
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
