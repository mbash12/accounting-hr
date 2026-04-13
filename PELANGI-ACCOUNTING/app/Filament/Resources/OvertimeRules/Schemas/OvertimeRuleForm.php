<?php

namespace App\Filament\Resources\OvertimeRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        Toggle::make('is_default')
                            ->label(__('Default'))
                            ->default(false),
                        Toggle::make('is_active')
                            ->label(__('Aktif'))
                            ->default(true),
                        TextInput::make('base_hourly_rate_divisor')
                            ->label(__('Pembagi Gaji Per Jam'))
                            ->numeric()
                            ->default(173.00)
                            ->required()
                            ->helperText(__('Standar adalah 173 untuk gaji bulanan')),
                        TextInput::make('workday_first_hour_multiplier')
                            ->label(__('Pengali Jam Pertama (Hari Kerja)'))
                            ->numeric()
                            ->default(1.50)
                            ->required(),
                        TextInput::make('workday_subsequent_hour_multiplier')
                            ->label(__('Pengali Jam Berikutnya (Hari Kerja)'))
                            ->numeric()
                            ->default(2.00)
                            ->required(),
                        TextInput::make('holiday_multiplier')
                            ->label(__('Pengali Hari Libur'))
                            ->numeric()
                            ->default(2.00)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
