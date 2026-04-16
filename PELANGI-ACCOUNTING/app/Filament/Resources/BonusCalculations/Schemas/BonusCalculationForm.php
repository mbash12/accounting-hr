<?php

namespace App\Filament\Resources\BonusCalculations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class BonusCalculationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informasi Bonus'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nama'))
                            ->required()
                            ->maxLength(200)
                            ->placeholder(__('Contoh: Bonus Tahunan 2026')),
                        Select::make('year')
                            ->label(__('Tahun'))
                            ->options(array_combine(range(now()->year - 1, now()->year + 5), range(now()->year - 1, now()->year + 5)))
                            ->default(now()->year)
                            ->required(),
                        DatePicker::make('payout_date')
                            ->label(__('Tanggal Payout'))
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
                        Toggle::make('is_taxable')
                            ->label(__('Hitung Pajak (PPh21)'))
                            ->default(true),
                        TextInput::make('description')
                            ->label(__('Deskripsi'))
                            ->columnSpan(2),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Ringkasan Bonus'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('total_amount')->label(__('Total Bonus'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_pph21')->label(__('Total PPh21'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
