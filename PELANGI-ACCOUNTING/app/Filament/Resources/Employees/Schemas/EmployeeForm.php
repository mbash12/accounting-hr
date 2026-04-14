<?php

namespace App\Filament\Resources\Employees\Schemas;

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
                Section::make(__('Informasi Personal & Pekerjaan'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nama'))
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
                            ->label(__('ID Karyawan'))
                            ->disabled()
                            ->placeholder(__('Otomatis')),
                        TextInput::make('nik')
                            ->label(__('NIK (KTP)'))
                            ->maxLength(16),
                        Select::make('department_id')
                            ->label(__('Departemen'))
                            ->relationship(
                                name: 'department', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => \App\Services\CompanyFilterService::applyCompanyFilter($query)
                            )
                            ->searchable()
                            ->preload(),
                        TextInput::make('position')
                            ->label(__('Jabatan'))
                            ->maxLength(100),
                        DatePicker::make('hire_date')
                            ->label(__('Tanggal Bergabung')),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'permanent' => __('Tetap'),
                                'contract' => __('Kontrak'),
                                'internship' => __('Magang'),
                                'probation' => __('Masa Percobaan'),
                            ])
                            ->default('probation')
                            ->required(),
                        TextInput::make('basic_salary')
                            ->label(__('Gaji Pokok'))
                            ->numeric()
                            ->prefix('IDR')
                            ->default(0),
                        Toggle::make('is_active')
                            ->label(__('Aktif'))
                            ->default(true),
                    ])->columns(2),
                Section::make(__('Pajak & BPJS'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('npwp')
                            ->label(__('NPWP'))
                            ->maxLength(20),
                        Select::make('ptkp_status')
                            ->label(__('Status PTKP'))
                            ->options([
                                'TK/0' => 'TK/0 (Lajang, 0 Tanggungan)',
                                'TK/1' => 'TK/1 (Lajang, 1 Tanggungan)',
                                'TK/2' => 'TK/2 (Lajang, 2 Tanggungan)',
                                'TK/3' => 'TK/3 (Lajang, 3 Tanggungan)',
                                'K/0' => 'K/0 (Menikah, 0 Tanggungan)',
                                'K/1' => 'K/1 (Menikah, 1 Tanggungan)',
                                'K/2' => 'K/2 (Menikah, 2 Tanggungan)',
                                'K/3' => 'K/3 (Menikah, 3 Tanggungan)',
                                'KI/0' => 'KI/0 (Menikah + Penghasilan Pasangan, 0 Tanggungan)',
                                'KI/1' => 'KI/1 (Menikah + Penghasilan Pasangan, 1 Tanggungan)',
                                'KI/2' => 'KI/2 (Menikah + Penghasilan Pasangan, 2 Tanggungan)',
                                'KI/3' => 'KI/3 (Menikah + Penghasilan Pasangan, 3 Tanggungan)',
                            ])
                            ->default('TK/0')
                            ->searchable()
                            ->required(),
                        TextInput::make('bpjs_kesehatan_number')
                            ->label(__('No. BPJS Kesehatan'))
                            ->maxLength(50),
                        TextInput::make('bpjs_ketenagakerjaan_number')
                            ->label(__('No. BPJS Ketenagakerjaan'))
                            ->maxLength(50),
                    ])->columns(2),
                Section::make(__('Rekening Bank'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('bank_name')
                            ->label(__('Nama Bank'))
                            ->maxLength(100),
                        TextInput::make('bank_account_number')
                            ->label(__('Nomor Rekening'))
                            ->maxLength(50),
                        TextInput::make('bank_account_holder')
                            ->label(__('Nama Pemilik Rekening'))
                            ->maxLength(200),
                    ])->columns(2),
            ]);
    }
}
