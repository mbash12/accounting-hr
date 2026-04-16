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
                Section::make(__('Detail Hari Libur'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nama'))
                            ->required()
                            ->maxLength(200),
                        DatePicker::make('date')
                            ->label(__('Tanggal'))
                            ->required(),
                        Toggle::make('is_cuti_bersama')
                            ->label(__('Cuti Bersama'))
                            ->default(false),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
