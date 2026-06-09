<?php

namespace App\Filament\Resources\BankReconciliations;

use App\Filament\Resources\BankReconciliations\Pages\ListBankReconciliations;
use App\Filament\Resources\BankReconciliations\Pages\ViewBankReconciliation;
use App\Models\BankReconciliation;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class BankReconciliationResource extends Resource
{
    protected static ?string $model = BankReconciliation::class;

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public static function getNavigationLabel(): string
    {
        return __('Bank Reconciliations');
    }

    public static function getModelLabel(): string
    {
        return __('Bank Reconciliation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bank Reconciliations');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bankAccount.account_name')
                    ->label(__('Bank Account'))
                    ->formatStateUsing(fn($record) => $record->bankAccount
                        ? "{$record->bankAccount->account_number} - {$record->bankAccount->account_name}"
                        : '—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('statement_date')
                    ->label(__('Statement Date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_summary')
                    ->label(__('Matched'))
                    ->state(function ($record) {
                        $matchedCount = $record->items()->where('match_status', 'matched')->count();
                        $totalCount = $record->items()->count();
                        return "{$matchedCount} / {$totalCount}";
                    }),

                Tables\Columns\TextColumn::make('difference')
                    ->label(__('Unmatched Amount'))
                    ->money('IDR')
                    ->state(function ($record) {
                        return app(\App\Services\BankReconciliationService::class)->getCalculatedAmounts($record)['unmatched_amount'];
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'pending' => 'gray',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('statement_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'completed' => __('Completed'),
                        'failed' => __('Failed'),
                    ]),
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                session('selected_company_id') && session('selected_company_id') !== 'all',
                fn(Builder $query) => $query->where('company_id', session('selected_company_id'))
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankReconciliations::route('/'),
            'view' => ViewBankReconciliation::route('/{record}'),
        ];
    }
}
