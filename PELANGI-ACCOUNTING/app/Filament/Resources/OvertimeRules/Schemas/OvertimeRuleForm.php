<?php

namespace App\Filament\Resources\OvertimeRules\Schemas;

use App\Filament\Forms\Components\NumberInput;
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
                                modifyQueryUsing: function ($query) {
                                    $companyId = session('selected_company_id');
                                    if ($companyId) {
                                        $query->where('company_id', $companyId);
                                    } elseif (auth()->check()) {
                                        $ids = auth()->user()->companies()->pluck('companies.id');
                                        if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->helperText(__('Opsional: Biarkan kosong untuk berlaku di semua departemen')),
                        NumberInput::make('base_hourly_rate_divisor')
                            ->label(__('Pembagi Gaji Per Jam'))
                            ->required()
                            ->default(173.00)
                            ->helperText(__('Standar adalah 173 untuk gaji bulanan'))
                            ->decimal(true),
                        NumberInput::make('workday_first_hour_multiplier')
                            ->label(__('Pengali Jam Pertama (Hari Kerja)'))
                            ->required()
                            ->default(1.50)
                            ->decimal(true),
                        NumberInput::make('workday_subsequent_hour_multiplier')
                            ->label(__('Pengali Jam Berikutnya (Hari Kerja)'))
                            ->required()
                            ->default(2.00)
                            ->decimal(true),
                        NumberInput::make('holiday_multiplier')
                            ->label(__('Pengali Hari Libur'))
                            ->required()
                            ->default(2.00)
                            ->decimal(true),
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
