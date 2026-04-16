<?php

namespace App\Filament\Resources\PayrollPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PayrollPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informasi Periode Payroll'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nama'))
                            ->required()
                            ->maxLength(200)
                            ->placeholder(__('Contoh: Payroll Maret 2026')),
                        Toggle::make('apply_attendance_deduction')
                            ->label(__('Terapkan Potongan Kehadiran'))
                            ->helperText(__('Jika aktif, keterlambatan dan pulang cepat akan memotong gaji.'))
                            ->default(false)
                            ->hidden(),
                        Select::make('month')
                            ->label(__('Bulan'))
                            ->options([
                                1 => __('Januari'), 2 => __('Februari'), 3 => __('Maret'), 4 => __('April'),
                                5 => __('Mei'), 6 => __('Juni'), 7 => __('Juli'), 8 => __('Agustus'),
                                9 => __('September'), 10 => __('Oktober'), 11 => __('November'), 12 => __('Desember'),
                            ])
                            ->required(),
                        Select::make('year')
                            ->label(__('Tahun'))
                            ->options(array_combine(range(now()->year - 1, now()->year + 5), range(now()->year - 1, now()->year + 5)))
                            ->default(now()->year)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label(__('Tanggal Mulai'))
                            ->required(),
                        DatePicker::make('end_date')
                            ->label(__('Tanggal Selesai'))
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'draft' => __('Draft'),
                                'processed' => __('Diproses'),
                                'posted' => __('Diposting'),
                            ])
                            ->default('draft')
                            ->disabled()
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Ringkasan Payroll'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('total_gross_salary')->label(__('Total Gaji Bruto'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_deductions')->label(__('Total Potongan'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_net_salary')->label(__('Total Gaji Bersih'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_pph21')->label(__('Total PPh21'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_bpjs_employer')->label(__('Total BPJS (Perusahaan)'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_bpjs_employee')->label(__('Total BPJS (Karyawan)'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
