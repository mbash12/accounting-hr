<?php

namespace App\Filament\Resources\Permits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PermitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Karyawan'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Tipe'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sick' => __('Sakit'),
                        'annual_leave' => __('Cuti Tahunan'),
                        'unpaid_leave' => __('Cuti Diluar Tanggungan'),
                        'maternity_leave' => __('Cuti Melahirkan'),
                        'other_permit' => __('Izin Lainnya'),
                        default => $state,
                    }),
                TextColumn::make('start_date')
                    ->label(__('Mulai'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('Selesai'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Menunggu'),
                        'approved' => __('Disetujui'),
                        'rejected' => __('Ditolak'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
