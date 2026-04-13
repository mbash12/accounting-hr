<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;

class EmployeeLeaveQuotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Kuota Cuti Karyawan'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Karyawan'))
                            ->relationship(
                                name: 'employee', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => \App\Services\CompanyFilterService::applyCompanyFilter($query)
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('year')
                            ->label(__('Tahun'))
                            ->required()
                            ->numeric()
                            ->default(now()->year),
                        TextInput::make('total_quota')
                            ->label(__('Total Kuota (Hari)'))
                            ->required()
                            ->numeric()
                            ->default(12)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateRemaining($get, $set)),
                        TextInput::make('used_quota')
                            ->label(__('Kuota Terpakai (Hari)'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateRemaining($get, $set))
                            ->helperText(__('Sesuaikan manual jika sistem baru digunakan di tengah tahun.')),
                        TextInput::make('remaining_quota')
                            ->label(__('Sisa Kuota (Hari)'))
                            ->required()
                            ->numeric()
                            ->readOnly()
                            ->default(12),
                    ])->columns(2),
                Section::make(__('Sistem'))
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Select::make('company_id')
                            ->label(__('Perusahaan'))
                            ->relationship('company', 'name')
                            ->disabled(),
                        Select::make('created_by_user_id')
                            ->label(__('Dibuat Oleh'))
                            ->relationship('createdByUser', 'name')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    protected static function updateRemaining(Get $get, Set $set)
    {
        $total = (int) $get('total_quota');
        $used = (int) $get('used_quota');
        $set('remaining_quota', $total - $used);
    }
}
