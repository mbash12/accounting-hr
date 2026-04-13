<?php

namespace App\Filament\Resources\PayablePayments\Tables;

use App\Filament\Resources\PayablePayments\PayablePaymentResource;
use App\Filament\Actions\ViewJournalVoucherAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PayablePaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("payment_number")
                    ->label(__("Nomor Pembayaran"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("payment_date")
                    ->label(__("Tanggal Pembayaran"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("supplier.name")
                    ->label(__("Pemasok"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total_payment")
                    ->label(__("Jumlah Total Pembayaran"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("payment_method")
                    ->label(__("Metode Pembayaran"))
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
                    ->label(__("Nomor Referensi"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("Dibuat Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Diperbarui Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Dihapus Pada"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('payment_date_range')
                    ->form([
                        DatePicker::make('from')->label(__('Dari')),
                        DatePicker::make('until')->label(__('Sampai')),
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
                                'pending' => __('Menunggu'),
                                'completed' => __('Selesai'),
                                'failed' => __('Gagal'),
                                'cancelled' => __('Dibatalkan'),
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
                            ->label(__('Metode Pembayaran'))
                            ->options([
                                'cash' => __('Tunai'),
                                'bank_transfer' => __('Transfer Bank'),
                                'check' => __('Cek'),
                                'credit_card' => __('Kartu Kredit'),
                                'debit_card' => __('Kartu Debit'),
                                'online_payment' => __('Pembayaran Online'),
                                'other' => __('Lainnya'),
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
                    ViewJournalVoucherAction::make(),
                    \Filament\Actions\Action::make('print')
                        ->label(__('Print Invoice'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (PayablePaymentResource $resource, \App\Models\PayablePayment $record): string => route('payable-payment.print', $record))
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



