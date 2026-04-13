<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('User Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email address'))
                            ->email()
                            ->required(),
                        // DateTimePicker::make('email_verified_at')
                        //     ->label(__('Email Verified At'))
                        //     ,
                        TextInput::make('password')
                            ->password()
                            ->label(__('Password'))
                            ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText(fn (string $context): string => $context === 'edit' ? __('Leave empty to keep current password') : __('Enter a secure password')),

                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->placeholder(__('Select roles...')),
                    ])
                    ->columns(2),
                Section::make(__('Company Assignment'))
                    ->collapsible()
                    ->schema([
                        Select::make('companies')
                            ->relationship('companies', 'name', fn ($query) => $query->select('companies.id', 'companies.name'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->placeholder(__('Select companies...')),
                    ])
                    ,
            ]);
    }
}
