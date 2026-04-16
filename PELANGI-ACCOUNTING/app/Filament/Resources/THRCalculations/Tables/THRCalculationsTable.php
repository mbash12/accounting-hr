<?php

namespace App\Filament\Resources\THRCalculations\Tables;

use App\Models\THRCalculation;
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
                    ->label(__('Nama'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('year')
                    ->label(__('Tahun'))
                    ->sortable(),
                TextColumn::make('payout_date')
                    ->label(__('Tanggal Payout'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'processed' => __('Diproses'),
                        'posted' => __('Diposting'),
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
                        ->label(__('Hitung THR'))
                        ->icon('heroicon-o-cpu-chip')
                        ->requiresConfirmation()
                        ->visible(fn (THRCalculation $record): bool => $record->status !== 'posted')
                        ->action(function (THRCalculation $record, PayrollService $service) {
                            try {
                                $service->calculateTHRForPeriod($record);
                                Notification::make()
                                    ->title(__('THR berhasil dihitung'))
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('Gagal menghitung THR'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        }),
                    Action::make('postToLedger')
                        ->label(__('Posting ke Jurnal'))
                        ->icon('heroicon-o-book-open')
                        ->requiresConfirmation()
                        ->visible(fn (THRCalculation $record): bool => $record->status === 'processed')
                        ->color('success')
                        ->action(function (THRCalculation $record, PayrollService $service) {
                            try {
                                $service->postTHRToLedger($record);
                                Notification::make()
                                    ->title(__('THR berhasil diposting ke jurnal'))
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('Gagal posting ke jurnal'))
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
