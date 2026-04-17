<?php

namespace App\Filament\Resources\PayrollPeriods\Tables;

use App\Models\PayrollPeriod;
use App\Services\PayrollService;
use App\Services\BcaPayrollService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PayrollPeriodsTable
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
                TextColumn::make('month')
                    ->label(__('Bulan'))
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => __('Januari'), 2 => __('Februari'), 3 => __('Maret'), 4 => __('April'),
                        5 => __('Mei'), 6 => __('Juni'), 7 => __('Juli'), 8 => __('Agustus'),
                        9 => __('September'), 10 => __('Oktober'), 11 => __('November'), 12 => __('Desember'),
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('year')
                    ->label(__('Tahun'))
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
                TextColumn::make('total_net_salary')
                    ->label(__('Total Gaji Bersih'))
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('generatePayslips')
                        ->label(__('Generate Payslip'))
                        ->icon('heroicon-o-cpu-chip')
                        ->requiresConfirmation()
                        ->visible(fn (PayrollPeriod $record): bool => $record->status === 'draft')
                        ->action(function (PayrollPeriod $record, PayrollService $service) {
                            $service->generatePayslips($record);
                            Notification::make()
                                ->title(__('Payslip berhasil dibuat'))
                                ->success()
                                ->send();
                        }),
                    Action::make('postToLedger')
                        ->label(__('Posting ke Jurnal'))
                        ->icon('heroicon-o-book-open')
                        ->requiresConfirmation()
                        ->visible(fn (PayrollPeriod $record): bool => $record->status === 'processed')
                        ->color('success')
                        ->action(function (PayrollPeriod $record, PayrollService $service) {
                            $service->postToLedger($record);
                            Notification::make()
                                ->title(__('Payroll berhasil diposting ke jurnal'))
                                ->success()
                                ->send();
                        }),
                    Action::make('exportBca')
                        ->label(__('Export BCA Payroll'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->visible(fn (PayrollPeriod $record): bool => $record->status !== 'draft')
                        ->action(function (PayrollPeriod $record, BcaPayrollService $service) {
                            $csv = $service->generateCsv($record);
                            $filename = 'BCA_Payroll_' . str_replace(' ', '_', $record->name) . '.csv';
                            
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
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
