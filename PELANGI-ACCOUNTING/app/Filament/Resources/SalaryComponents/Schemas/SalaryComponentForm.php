<?php

namespace App\Filament\Resources\SalaryComponents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalaryComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Salary Component Details'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('Code'))
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(200),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'allowance' => __('Allowance'),
                                'deduction' => __('Deduction'),
                            ])
                            ->required(),
                        Select::make('account_id')
                            ->label(__('GL Account'))
                            ->relationship(
                                name: 'account', 
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query) {
                                    $companyId = session('selected_company_id');
                                    if ($companyId) {
                                        $query->where('company_id', $companyId);
                                    } elseif (auth()->check()) {
                                        $ids = auth()->user()->companies()->pluck('companies.id');
                                        if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                                    }
                                    $query->where('is_header', false);
                                }
                            )
                            ->searchable()
                            ->preload(),
                        Toggle::make('is_fixed')
                            ->label(__('Fixed Component'))
                            ->default(false),
                        Toggle::make('is_taxable')
                            ->label(__('Taxable (PPh21)'))
                            ->default(true),
                        Toggle::make('is_bpjs_base')
                            ->label(__('BPJS Calculation Base'))
                            ->default(true),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
