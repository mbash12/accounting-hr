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
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month')
                    ->label(__('Month'))
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => __('January'), 2 => __('February'), 3 => __('March'), 4 => __('April'),
                        5 => __('May'), 6 => __('June'), 7 => __('July'), 8 => __('August'),
                        9 => __('September'), 10 => __('October'), 11 => __('November'), 12 => __('December'),
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('year')
                    ->label(__('Year'))
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
                TextColumn::make('total_net_salary')
                    ->label(__('Total Net Salary'))
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
                                ->title(__('Payslip generated successfully'))
                                ->success()
                                ->send();
                        }),
                    Action::make('downloadPayslips')
                        ->label(__('Download Payslip'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn (PayrollPeriod $record): bool => $record->payslips()->exists())
                        ->url(fn (PayrollPeriod $record): string => route('payslip.pdf.period', $record->id))
                        ->openUrlInNewTab(),
                    Action::make('postToLedger')
                        ->label(__('Post to Journal'))
                        ->icon('heroicon-o-book-open')
                        ->requiresConfirmation()
                        ->visible(fn (PayrollPeriod $record): bool => $record->status === 'processed')
                        ->color('success')
                        ->action(function (PayrollPeriod $record, PayrollService $service) {
                            try {
                                $entry = $service->postToLedger($record);
                                if ($entry === null) {
                                    Notification::make()
                                        ->title(__('Journal entry skipped'))
                                        ->body(__('Configure Payroll account mappings before posting.'))
                                        ->warning()
                                        ->send();

                                    return;
                                }
                                Notification::make()
                                    ->title(__('Payroll posted to journal successfully'))
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
