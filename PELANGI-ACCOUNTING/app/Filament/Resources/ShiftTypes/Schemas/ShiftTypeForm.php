<?php

namespace App\Filament\Resources\ShiftTypes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShiftTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Shift Type'))
                ->columns(3)
                ->schema([
                    TextInput::make('code')
                        ->label(__('Code'))
                        ->helperText(__('Short code shown in the grid (e.g. R, 0, 1, 2, 3, RS1)'))
                        ->required()
                        ->maxLength(10)
                        ->columnSpan(1),
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(2),
                    TimePicker::make('start_time')
                        ->label(__('Start Time'))
                        ->seconds(false)
                        ->columnSpan(1),
                    TimePicker::make('end_time')
                        ->label(__('End Time'))
                        ->seconds(false)
                        ->columnSpan(1),
                    Toggle::make('is_off')
                        ->label(__('Is Off Day'))
                        ->helperText(__('Mark if this code represents a day off'))
                        ->columnSpan(1),
                    ColorPicker::make('color')
                        ->label(__('Cell Background'))
                        ->required()
                        ->default('#cfe2ff')
                        ->columnSpan(1),
                    ColorPicker::make('text_color')
                        ->label(__('Cell Text'))
                        ->required()
                        ->default('#000000')
                        ->columnSpan(1),
                    Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true)
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),
        ]);
    }
}
