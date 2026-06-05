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
                        ->numeric()
                        ->maxLength(50),
                    TextInput::make('account_name')
                        ->label(__('Account Name'))
                        ->required()
                        ->maxLength(200),
                    Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}
