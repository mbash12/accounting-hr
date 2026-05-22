<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas\Schemas;

use App\Filament\Forms\Components\NumberInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class EmployeeLeaveQuotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Employee Leave Quota'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Employee'))
                            ->relationship(
                                name: 'employee', 
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
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('year')
                            ->label(__('Year'))
                            ->required()
                            ->numeric()
                            ->default(now()->year)
                            ->minLength(4)
                            ->maxLength(4),
                        NumberInput::make('total_quota')
                            ->label(__('Total Quota (Days)'))
                            ->required()
                            ->default(12)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateRemaining($get, $set))
                            ->decimal(false),
                        NumberInput::make('used_quota')
                            ->label(__('Used Quota (Days)'))
                            ->required()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateRemaining($get, $set))
                            ->helperText(__('Adjust manually if the system was adopted mid-year.'))
                            ->decimal(false),
                        NumberInput::make('remaining_quota')
                            ->label(__('Remaining Quota (Days)'))
                            ->required()
                            ->readOnly()
                            ->default(12)
                            ->decimal(false),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('System'))
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Select::make('company_id')
                            ->label(__('Company'))
                            ->relationship('company', 'name')
                            ->disabled(),
                        Select::make('created_by_user_id')
                            ->label(__('Created By'))
                            ->relationship('createdByUser', 'name')
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->hidden(),
            ]);
    }

    protected static function updateRemaining(Get $get, Set $set)
    {
        $total = (int) NumberInput::parseToFloat($get('total_quota') ?? 0, true);
        $used = (int) NumberInput::parseToFloat($get('used_quota') ?? 0, true);
        $set('remaining_quota', $total - $used);
    }
}
