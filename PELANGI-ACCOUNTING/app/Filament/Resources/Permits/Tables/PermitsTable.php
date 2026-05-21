<?php

namespace App\Filament\Resources\Permits\Tables;

use App\Filament\Actions\ExportPermitsAction;
use App\Filament\Actions\ImportPermitsAction;
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

class PermitsTable
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
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sick' => __('Sick'),
                        'annual_leave' => __('Annual Leave (Legacy)'),
                        'unpaid_leave' => __('Unpaid Leave (Legacy)'),
                        'maternity_leave' => __('Maternity Leave (Legacy)'),
                        'other_permit' => __('Other Permit (Legacy)'),
                        'annual' => __('Annual Leave'),
                        'marry' => __('Marriage Leave'),
                        'kids_marry' => __('Child Marriage Leave'),
                        'khitan' => __('Child Circumcision/Baptism Leave'),
                        'family_death' => __('Immediate Family Bereavement Leave'),
                        'maternity' => __('Maternity Leave'),
                        'maternity_husband' => __('Paternity Leave'),
                        'maternity_death' => __('Miscarriage Leave'),
                        'force_majure' => __('Force Majeure / Natural Disaster'),
                        'nodn_sick' => __('Sick Without Certificate'),
                        'sudden' => __('Emergency Leave'),
                        'others' => __('Permit'),
                        default => $state,
                    }),
                TextColumn::make('start_date')
                    ->label(__('Start'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('End'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Pending'),
                        'approved' => __('Approved'),
                        'rejected' => __('Rejected'),
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
            ->toolbarActions([
                ImportPermitsAction::make(),
                ExportPermitsAction::make(),
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
