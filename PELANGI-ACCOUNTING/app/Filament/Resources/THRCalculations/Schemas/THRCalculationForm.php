<?php

namespace App\Filament\Resources\THRCalculations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class THRCalculationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('THR Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(200)
                            ->placeholder(__('Example: THR Eid Al-Fitr 2026')),
                        Select::make('year')
                            ->label(__('Year'))
                            ->options(array_combine(range(now()->year - 1, now()->year + 5), range(now()->year - 1, now()->year + 5)))
                            ->default(now()->year)
                            ->required(),
                        DatePicker::make('payout_date')
                            ->label(__('Payout Date'))
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'draft' => __('Draft'),
                                'processed' => __('Processed'),
                                'posted' => __('Posted'),
                            ])
                            ->default('draft')
                            ->disabled()
                            ->required(),
                        Toggle::make('is_taxable')
                            ->label(__('Calculate Tax (PPh21)'))
                            ->default(true),
                        TextInput::make('description')
                            ->label(__('Description'))
                            ->columnSpan(2),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('THR Summary'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('total_amount')->label(__('Total THR'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_pph21')->label(__('Total PPh21'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
