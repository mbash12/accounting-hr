<?php

namespace App\Filament\Resources\THRCalculations\Tables;

use App\Models\THRCalculation;
use App\Services\BcaPayrollService;
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

class THRCalculationsTable
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
                    ->label(__('Total THR'))
                    ->money('IDR')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('calculateTHR')
                        ->label(__('Calculate THR'))
                        ->icon('heroicon-o-cpu-chip')
                        ->requiresConfirmation()
                        ->visible(fn (THRCalculation $record): bool => $record->status !== 'posted')
                        ->action(function (THRCalculation $record, PayrollService $service) {
                            try {
                                $service->calculateTHRForPeriod($record);
                                Notification::make()
                                    ->title(__('THR calculated successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('Failed to calculate THR'))
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
                        ->visible(fn (THRCalculation $record): bool => $record->status === 'processed')
                        ->color('success')
                        ->action(function (THRCalculation $record, PayrollService $service) {
                            try {
                                $entry = $service->postTHRToLedger($record);
                                if ($entry === null) {
                                    Notification::make()
                                        ->title(__('Journal entry skipped'))
                                        ->body(__('Configure Payroll account mappings (THR/Salary Expense, Salary Payable, PPh21) before posting.'))
                                        ->warning()
                                        ->send();

                                    return;
                                }
                                Notification::make()
                                    ->title(__('THR posted to journal successfully'))
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
                    Action::make('exportBca')
                        ->label(__('Export BCA Payroll'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->visible(fn (THRCalculation $record): bool => $record->status !== 'draft')
                        ->action(function (THRCalculation $record, BcaPayrollService $service) {
                            $csv = $service->generateCsvForTHR($record);
                            $filename = 'BCA_THR_' . str_replace(' ', '_', $record->name) . '.csv';

                            return response()->streamDownload(function () use ($csv) {
                                echo $csv;
                            }, $filename, [
                                'Content-Type' => 'text/csv',
                            ]);
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
