<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Filament\Actions\ExportAttendancesAction;
use App\Filament\Actions\ImportAttendancesAction;
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

class AttendancesTable
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
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label(__('Check-in Time'))
                    ->time(),
                TextColumn::make('check_out')
                    ->label(__('Check-out Time'))
                    ->time(),
                TextColumn::make('late_minutes')
                    ->label(__('Late (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('early_departure_minutes')
                    ->label(__('Early (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'present' => __('Present'),
                        'late' => __('Late'),
                        'absent' => __('Absent'),
                        'permit' => __('Permit'),
                        'leave' => __('Leave'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'absent' => 'danger',
                        'permit' => 'info',
                        'leave' => 'gray',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([
                ImportAttendancesAction::make(),
                ExportAttendancesAction::make(),
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
