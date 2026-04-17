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
                    ->label(__('Karyawan'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Tanggal'))
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label(__('Jam Masuk'))
                    ->time(),
                TextColumn::make('check_out')
                    ->label(__('Jam Keluar'))
                    ->time(),
                TextColumn::make('late_minutes')
                    ->label(__('Lbt (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('early_departure_minutes')
                    ->label(__('Plg Cepat (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'present' => __('Hadir'),
                        'late' => __('Terlambat'),
                        'absent' => __('Alpa'),
                        'permit' => __('Izin'),
                        'leave' => __('Cuti'),
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
