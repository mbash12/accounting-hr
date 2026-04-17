<?php

namespace App\Filament\Resources\OvertimeRules\Tables;

use App\Filament\Actions\ExportOvertimeRulesAction;
use App\Filament\Actions\ImportOvertimeRulesAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OvertimeRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nama'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label(__('Departemen'))
                    ->placeholder(__('Semua Departemen'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_default')
                    ->label(__('Default'))
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label(__('Aktif'))
                    ->boolean(),
                TextColumn::make('base_hourly_rate_divisor')
                    ->label(__('Pembagi'))
                    ->sortable(),
                TextColumn::make('workday_first_hour_multiplier')
                    ->label(__('Pengali 1 (Kerja)')),
                TextColumn::make('workday_subsequent_hour_multiplier')
                    ->label(__('Pengali 2 (Kerja)')),
                TextColumn::make('holiday_multiplier')
                    ->label(__('Pengali Libur')),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([
                ImportOvertimeRulesAction::make(),
                ExportOvertimeRulesAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
