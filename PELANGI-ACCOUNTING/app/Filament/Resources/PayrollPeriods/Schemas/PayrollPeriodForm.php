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
                Section::make(__('Payroll Period Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(200)
                            ->placeholder(__('Example: Payroll March 2026')),
                        Toggle::make('apply_attendance_deduction')
                            ->label(__('Apply Attendance Deduction'))
                            ->helperText(__('If enabled, lateness and early departure will deduct salary.'))
                            ->default(false)
                            ->hidden(),
                        Select::make('month')
                            ->label(__('Month'))
                            ->options([
                                1 => __('January'), 2 => __('February'), 3 => __('March'), 4 => __('April'),
                                5 => __('May'), 6 => __('June'), 7 => __('July'), 8 => __('August'),
                                9 => __('September'), 10 => __('October'), 11 => __('November'), 12 => __('December'),
                            ])
                            ->required(),
                        Select::make('year')
                            ->label(__('Year'))
                            ->options(array_combine(range(now()->year - 1, now()->year + 5), range(now()->year - 1, now()->year + 5)))
                            ->default(now()->year)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required(),
                        DatePicker::make('end_date')
                            ->label(__('End Date'))
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
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Payroll Summary'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('total_gross_salary')->label(__('Total Gross Salary'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_deductions')->label(__('Total Deductions'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_net_salary')->label(__('Total Net Salary'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_pph21')->label(__('Total PPh21'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_bpjs_employer')->label(__('Total BPJS (Company)'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                        TextInput::make('total_bpjs_employee')->label(__('Total BPJS (Employee)'))->disabled()->numeric()->default(0)->mask(RawJs::make('$money($input, \',\', \'.\')'))->stripCharacters('.'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
