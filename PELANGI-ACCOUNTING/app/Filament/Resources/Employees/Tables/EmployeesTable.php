<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')
                    ->label(__('ID Karyawan'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Nama'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label(__('Departemen'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->label(__('Jabatan'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'permanent' => __('Tetap'),
                        'contract' => __('Kontrak'),
                        'internship' => __('Magang'),
                        'probation' => __('Masa Percobaan'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'permanent' => 'success',
                        'contract' => 'warning',
                        'internship' => 'info',
                        'probation' => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label(__('Aktif'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label(__('Gaji Pokok'))
                    ->money('IDR')
                    ->sortable(),
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
