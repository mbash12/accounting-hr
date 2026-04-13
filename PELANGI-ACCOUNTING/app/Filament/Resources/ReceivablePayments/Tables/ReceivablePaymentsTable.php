<?php

namespace App\Filament\Resources\ReceivablePayments\Tables;

use App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource;
use App\Filament\Actions\ViewJournalVoucherAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReceivablePaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("payment_number")
                    ->label(__("Payment No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("payment_date")
                    ->label(__("Payment Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("customer.name")
                    ->label(__("Customer"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("bankAccount.account_name")
                    ->label(__("Bank Account"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("total_payment")
                    ->label(__("Total Payment"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("payment_method")
                    ->label(__("Payment Method"))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make("reference_no")
                    ->label(__("Reference No."))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("Created At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Updated At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Deleted At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('payment_date_range')
                    ->form([
                        DatePicker::make('from')->label(__('From')),
                        DatePicker::make('until')->label(__('Until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $from) => $q->whereDate('payment_date', '>=', $from))
                            ->when($data['until'] ?? null, fn($q, $until) => $q->whereDate('payment_date', '<=', $until));
                    }),
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => __('Pending'),
                                'completed' => __('Completed'),
                                'failed' => __('Failed'),
                                'cancelled' => __('Cancelled'),
                            ])
                            ->multiple(),
                    ])
                    ->query(fn($query, array $data) => 
                        $query->when($data['status'] ?? null, fn($q, $status) => 
                            $q->whereIn('status', $status)
                        )
                    ),
                Filter::make('payment_method')
                    ->form([
                        Select::make('payment_method')
                            ->label(__('Payment Method'))
                            ->options([
                                'cash' => __('Cash'),
                                'bank_transfer' => __('Bank Transfer'),
                                'check' => __('Check'),
                                'credit_card' => __('Credit Card'),
                                'debit_card' => __('Debit Card'),
                                'online_payment' => __('Online Payment'),
                                'other' => __('Other'),
                            ])
                            ->multiple(),
                    ])
                    ->query(fn($query, array $data) => 
                        $query->when($data['payment_method'] ?? null, fn($q, $method) => 
                            $q->whereIn('payment_method', $method)
                        )
                    ),
                TrashedFilter::make(),
            ])
            ->defaultSort('payment_date', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label(__('Detail'))
                        ->icon('heroicon-o-eye'),
                    ViewJournalVoucherAction::make(),
                    \Filament\Actions\Action::make('print')
                        ->label(__('Print Invoice'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (ReceivablePaymentResource $resource, \App\Models\ReceivablePayment $record): string => route('receivable-payment.print', $record))
                        ->openUrlInNewTab(),
                    EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}











