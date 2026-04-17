<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas\Tables;

use App\Filament\Actions\ExportEmployeeLeaveQuotasAction;
use App\Filament\Actions\ImportEmployeeLeaveQuotasAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeeLeaveQuotasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Karyawan'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('year')
                    ->label(__('Tahun'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_quota')
                    ->label(__('Total (Hari)'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('used_quota')
                    ->label(__('Terpakai (Hari)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('remaining_quota')
                    ->label(__('Sisa (Hari)'))
                    ->numeric()
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([
                ImportEmployeeLeaveQuotasAction::make(),
                ExportEmployeeLeaveQuotasAction::make(),
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
