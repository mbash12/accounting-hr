<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Bank Account Information'))
                ->schema([
                    Select::make('bank_id')
                        ->label(__('Bank'))
                        ->relationship('bank', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('account_number')
                        ->label(__('Account Number'))
                        ->required()
                        ->maxLength(50),
                    TextInput::make('account_name')
                        ->label(__('Account Name'))
                        ->required()
                        ->maxLength(200),
                    Select::make('account_type')
                        ->label(__('Account Type'))
                        ->options([
                            'checking' => __('Checking'),
                            'savings' => __('Savings'),
                            'credit_card' => __('Credit Card'),
                            'investment' => __('Investment'),
                        ])
                        ->default('checking')
                        ->required(),
                    Select::make('company_id')
                        ->label(__('Company'))
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('balance')
                        ->label(__('Opening Balance'))
                        ->numeric()
                        ->default(0)
                        ->prefix('Rp'),
                    Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}
