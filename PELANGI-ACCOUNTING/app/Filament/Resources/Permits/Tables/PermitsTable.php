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
            ->defaultSort('id', 'desc')
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
                        'annual_leave' => __('Cuti Tahunan (Legacy)'),
                        'unpaid_leave' => __('Cuti Diluar Tanggungan (Legacy)'),
                        'maternity_leave' => __('Cuti Melahirkan (Legacy)'),
                        'other_permit' => __('Izin Lainnya (Legacy)'),
                        'annual' => __('Cuti Tahunan'),
                        'marry' => __('Cuti Menikah'),
                        'kids_marry' => __('Cuti Menikahkan Anak'),
                        'khitan' => __('Cuti Khitan/Baptis Anak'),
                        'family_death' => __('Cuti Keluarga Inti Meninggal'),
                        'maternity' => __('Cuti Melahirkan'),
                        'maternity_husband' => __('Cuti Istri Melahirkan'),
                        'maternity_death' => __('Cuti Keguguran'),
                        'force_majure' => __('Izin Bencana Alam'),
                        'nodn_sick' => __('Sakit Tanpa Surat'),
                        'sudden' => __('Izin Mendadak'),
                        'others' => __('Izin'),
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
