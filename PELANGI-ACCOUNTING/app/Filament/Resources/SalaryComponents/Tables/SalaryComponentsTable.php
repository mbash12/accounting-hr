<?php

namespace App\Filament\Resources\SalaryComponents\Tables;

use App\Filament\Actions\ExportSalaryComponentsAction;
use App\Filament\Actions\ImportSalaryComponentsAction;
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

class SalaryComponentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'allowance' => __('Allowance'),
                        'deduction' => __('Deduction'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => $state === 'allowance' ? 'success' : 'danger'),
                IconColumn::make('is_fixed')
                    ->label(__('Fixed'))
                    ->boolean(),
                IconColumn::make('is_taxable')
                    ->label(__('Tax'))
                    ->boolean(),
                IconColumn::make('is_bpjs_base')
                    ->label(__('BPJS Base'))
                    ->boolean(),
                TextColumn::make('account.name')
                    ->label(__('GL Account')),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([
                ImportSalaryComponentsAction::make(),
                ExportSalaryComponentsAction::make(),
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
