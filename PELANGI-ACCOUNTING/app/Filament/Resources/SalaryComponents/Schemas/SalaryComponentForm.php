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
                Section::make(__('Detail Komponen Gaji'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('Kode'))
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->label(__('Nama'))
                            ->required()
                            ->maxLength(200),
                        Select::make('type')
                            ->label(__('Tipe'))
                            ->options([
                                'allowance' => __('Tunjangan'),
                                'deduction' => __('Potongan'),
                            ])
                            ->required(),
                        Select::make('account_id')
                            ->label(__('Akun GL'))
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
                            ->label(__('Komponen Tetap'))
                            ->default(false),
                        Toggle::make('is_taxable')
                            ->label(__('Kena Pajak (PPh21)'))
                            ->default(true),
                        Toggle::make('is_bpjs_base')
                            ->label(__('Dasar Perhitungan BPJS'))
                            ->default(true),
                        Toggle::make('is_active')
                            ->label(__('Aktif'))
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
