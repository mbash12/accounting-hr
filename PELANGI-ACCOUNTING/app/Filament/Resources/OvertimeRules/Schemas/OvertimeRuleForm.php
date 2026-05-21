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
                Section::make(__('Overtime Rule Details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(100),
                        Select::make('department_id')
                            ->label(__('Department'))
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
                            ->helperText(__('Optional: Leave empty to apply to all departments')),
                        NumberInput::make('base_hourly_rate_divisor')
                            ->label(__('Hourly Rate Divisor'))
                            ->required()
                            ->default(173.00)
                            ->helperText(__('Standard is 173 for monthly salary'))
                            ->decimal(true),
                        NumberInput::make('workday_first_hour_multiplier')
                            ->label(__('First Hour Multiplier (Working Day)'))
                            ->required()
                            ->default(1.50)
                            ->decimal(true),
                        NumberInput::make('workday_subsequent_hour_multiplier')
                            ->label(__('Subsequent Hour Multiplier (Working Day)'))
                            ->required()
                            ->default(2.00)
                            ->decimal(true),
                        NumberInput::make('holiday_multiplier')
                            ->label(__('Holiday Multiplier'))
                            ->required()
                            ->default(2.00)
                            ->decimal(true),
                        Toggle::make('is_default')
                            ->label(__('Default'))
                            ->default(false),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
