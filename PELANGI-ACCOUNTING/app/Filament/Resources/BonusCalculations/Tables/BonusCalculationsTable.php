<?php

namespace App\Filament\Resources\BonusCalculations\Tables;

use App\Models\BonusCalculation;
use App\Services\PayrollService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BonusCalculationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('year')
                    ->label(__('Year'))
                    ->sortable(),
                TextColumn::make('payout_date')
                    ->label(__('Payout Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'processed' => __('Processed'),
                        'posted' => __('Posted'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'processed' => 'warning',
                        'posted' => 'success',
                    }),
                TextColumn::make('total_amount')
                    ->label(__('Total Bonus'))
                    ->money('IDR')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('calculateBonus')
                        ->label(__('Calculate Bonus Tax'))
                        ->icon('heroicon-o-cpu-chip')
                        ->requiresConfirmation()
                        ->visible(fn (BonusCalculation $record): bool => $record->status !== 'posted')
                        ->action(function (BonusCalculation $record, PayrollService $service) {
                            try {
                                $service->calculateBonusForPeriod($record);
                                Notification::make()
                                    ->title(__('Bonus tax calculated successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('Failed to calculate bonus'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        }),
                    Action::make('postToLedger')
                        ->label(__('Post to Journal'))
                        ->icon('heroicon-o-book-open')
                        ->requiresConfirmation()
                        ->visible(fn (BonusCalculation $record): bool => $record->status === 'processed')
                        ->color('success')
                        ->action(function (BonusCalculation $record, PayrollService $service) {
                            try {
                                $service->postBonusToLedger($record);
                                Notification::make()
                                    ->title(__('Bonus posted to journal successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('Failed to post to journal'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        }),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
