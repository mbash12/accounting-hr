<?php

namespace App\Filament\Resources\OvertimeLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OvertimeLogForm
{
    public static function configure(Schema $schema, bool $disabled = false): Schema
    {
        return $schema
            ->components([
                Section::make(__('Pengajuan Lembur'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Karyawan'))
                            ->relationship(
                                name: 'employee',
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
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled($disabled),
                        DatePicker::make('date')
                            ->label(__('Tanggal'))
                            ->required()
                            ->disabled($disabled),
                        TextInput::make('hours')
                            ->label(__('Jam Lembur'))
                            ->numeric()
                            ->required()
                            ->minValue(0.5)
                            ->step(0.5)
                            ->disabled($disabled),
                        Toggle::make('is_holiday')
                            ->label(__('Hari Libur'))
                            ->default(false)
                            ->disabled($disabled),
                        Textarea::make('reason')
                            ->label(__('Alasan'))
                            ->columnSpanFull()
                            ->disabled($disabled),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'draft' => __('Draft'),
                                'approved' => __('Disetujui'),
                                'rejected' => __('Ditolak'),
                            ])
                            ->default('draft')
                            ->required()
                            ->disabled($disabled),
                        TextInput::make('calculated_amount')
                            ->label(__('Tunjangan Lembur'))
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->visible(fn ($record) => $record && $record->status === 'approved' && $record->calculated_amount > 0),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
