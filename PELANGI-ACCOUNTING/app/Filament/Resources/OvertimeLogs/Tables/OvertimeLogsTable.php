<?php

namespace App\Filament\Resources\OvertimeLogs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OvertimeLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Tanggal'))
                    ->date()
                    ->sortable(),
                TextColumn::make('hours')
                    ->label(__('Hours'))
                    ->sortable(),
                IconColumn::make('is_holiday')
                    ->label(__('Holiday'))
                    ->boolean(),
                TextColumn::make('calculated_amount')
                    ->label(__('Allowance'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'approved' => __('Approved'),
                        'rejected' => __('Rejected'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('reason')
                    ->label(__('Reason'))
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'draft' => __('Draft'),
                        'approved' => __('Approved'),
                        'rejected' => __('Rejected'),
                    ]),
                TrashedFilter::make(),
            ])
            ->toolbarActions([
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
