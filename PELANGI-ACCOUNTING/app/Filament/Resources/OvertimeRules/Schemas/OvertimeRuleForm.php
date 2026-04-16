<?php

namespace App\Filament\Resources\OvertimeRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class OvertimeRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Detail Aturan Lembur'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nama'))
                            ->required()
                            ->maxLength(100),
                        Select::make('department_id')
                            ->label(__('Departemen'))
                            ->relationship(
                                name: 'department', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => \App\Services\CompanyFilterService::applyCompanyFilter($query)
                            )
                            ->searchable()
                            ->preload()
                            ->helperText(__('Opsional: Biarkan kosong untuk berlaku di semua departemen')),
                        TextInput::make('base_hourly_rate_divisor')
                            ->label(__('Pembagi Gaji Per Jam'))
                            ->required()
                            ->numeric()
                            ->default(173.00)
                            ->helperText(__('Standar adalah 173 untuk gaji bulanan'))
                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                            ->stripCharacters('.'),
                        TextInput::make('workday_first_hour_multiplier')
                            ->label(__('Pengali Jam Pertama (Hari Kerja)'))
                            ->required()
                            ->numeric()
                            ->default(1.50)
                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                            ->stripCharacters('.'),
                        TextInput::make('workday_subsequent_hour_multiplier')
                            ->label(__('Pengali Jam Berikutnya (Hari Kerja)'))
                            ->required()
                            ->numeric()
                            ->default(2.00)
                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                            ->stripCharacters('.'),
                        TextInput::make('holiday_multiplier')
                            ->label(__('Pengali Hari Libur'))
                            ->required()
                            ->numeric()
                            ->default(2.00)
                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                            ->stripCharacters('.'),
                        Toggle::make('is_default')
                            ->label(__('Default'))
                            ->default(false),
                        Toggle::make('is_active')
                            ->label(__('Aktif'))
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
