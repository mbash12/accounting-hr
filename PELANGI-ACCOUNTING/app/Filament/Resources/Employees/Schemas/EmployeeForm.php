<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Filament\Forms\Components\NumberInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal & Employment Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(200),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null),
                        TextInput::make('employee_id')
                            ->label(__('Employee ID'))
                            ->disabled()
                            ->placeholder(__('Auto-generated')),
                        TextInput::make('nik')
                            ->label(__('NIK (ID Card)'))
                            ->inputMode('numeric')
                            ->rule('digits:16')
                            ->maxLength(16)
                            ->extraInputAttributes(['maxlength' => '16']),
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
                            ->preload(),
                        TextInput::make('position')
                            ->label(__('Position'))
                            ->maxLength(100),
                        DatePicker::make('hire_date')
                            ->label(__('Join Date')),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'permanent' => __('Permanent'),
                                'contract' => __('Contract'),
                                'internship' => __('Internship'),
                                'probation' => __('Probation'),
                            ])
                            ->default('probation')
                            ->required(),
                        NumberInput::make('basic_salary')
                            ->label(__('Basic Salary'))
                            ->required()
                            ->prefix('IDR')
                            ->default(0)
                            ->decimal(false),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ])->columns(2),
                Section::make(__('Tax & BPJS'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('npwp')
                            ->label(__('NPWP'))
                            ->mask('99.999.999.9-999.999')
                            ->placeholder('00.000.000.0-000.000')
                            ->regex('/^\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}$/')
                            ->maxLength(20),
                        Select::make('ptkp_status')
                            ->label(__('PTKP Status'))
                            ->options([
                                'TK/0' => 'TK/0 (Single, 0 Dependents)',
                                'TK/1' => 'TK/1 (Single, 1 Dependent)',
                                'TK/2' => 'TK/2 (Single, 2 Dependents)',
                                'TK/3' => 'TK/3 (Single, 3 Dependents)',
                                'K/0' => 'K/0 (Married, 0 Dependents)',
                                'K/1' => 'K/1 (Married, 1 Dependent)',
                                'K/2' => 'K/2 (Married, 2 Dependents)',
                                'K/3' => 'K/3 (Married, 3 Dependents)',
                                'KI/0' => 'KI/0 (Married + Spouse Income, 0 Dependents)',
                                'KI/1' => 'KI/1 (Married + Spouse Income, 1 Dependent)',
                                'KI/2' => 'KI/2 (Married + Spouse Income, 2 Dependents)',
                                'KI/3' => 'KI/3 (Married + Spouse Income, 3 Dependents)',
                            ])
                            ->default('TK/0')
                            ->searchable()
                            ->required(),
                        TextInput::make('bpjs_kesehatan_number')
                            ->label(__('BPJS Health No.'))
                            ->inputMode('numeric')
                            ->rule('digits:13')
                            ->maxLength(13)
                            ->extraInputAttributes(['maxlength' => '13']),
                        TextInput::make('bpjs_ketenagakerjaan_number')
                            ->label(__('BPJS Employment No.'))
                            ->inputMode('numeric')
                            ->rule('digits:11')
                            ->maxLength(11)
                            ->extraInputAttributes(['maxlength' => '11']),
                    ])->columns(2),
                Section::make(__('Bank Account'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('bank_name')
                            ->label(__('Bank Name'))
                            ->maxLength(100),
                        TextInput::make('bank_account_number')
                            ->label(__('Account Number'))
                            ->numeric()
                            ->maxLength(50),
                        TextInput::make('bank_account_holder')
                            ->label(__('Account Holder Name'))
                            ->maxLength(200),
                    ])->columns(2),
            ]);
    }
}
