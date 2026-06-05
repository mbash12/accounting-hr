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
                    Select::make('coa_account_id')
                        ->label(__('COA Account'))
                        ->options(function () {
                            $selectedCompanyId = session('selected_company_id');
                            $q = \App\Models\Account::where('is_header', false)
                                ->where('is_active', true);
                            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                $q->where('company_id', $selectedCompanyId);
                            }
                            return $q->orderBy('code')->get()
                                ->mapWithKeys(fn ($a) => [$a->id => $a->code . ' - ' . $a->name]);
                        })
                        ->searchable()
                        ->preload()
                        ->helperText('Link to Chart of Accounts for journal entries'),
                    Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}
