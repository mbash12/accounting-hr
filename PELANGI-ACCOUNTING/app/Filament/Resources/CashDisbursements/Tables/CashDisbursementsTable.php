<?php

namespace App\Filament\Resources\CashDisbursements\Tables;

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

class CashDisbursementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("disbursement_number")
                    ->label(__("Disbursement No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("fromAccount.name")
                    ->label(__("Cash/Bank Account"))
                    ->formatStateUsing(fn ($record) => $record->fromAccount ? "{$record->fromAccount->code} - {$record->fromAccount->name}" : '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total")
                    ->label(__("Total"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => \Illuminate\Support\Str::headline($state))
                    ->searchable()
                    ->sortable(),
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
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from')->label(__('From')),
                        DatePicker::make('until')->label(__('Until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $from) => $q->whereDate('date', '>=', $from))
                            ->when($data['until'] ?? null, fn($q, $until) => $q->whereDate('date', '<=', $until));
                    }),
                Filter::make('from_account_id')
                    ->form([
                        Select::make('from_account_id')
                            ->label(__('Bank Account'))
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\BankAccount::query()
                                    ->where('is_active', true);

                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                            ->orWhereNull('company_id'); // allow global bank accounts
                                    });
                                }

                                return $query->orderBy('account_name')
                                    ->limit(50)
                                    ->pluck('account_name', 'id');
                            })
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(fn($query, array $data) => $query->when($data['from_account_id'] ?? null, fn($q, $id) => $q->where('from_account_id', $id))),
                TrashedFilter::make(),
            ])
            ->defaultSort('date', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewJournalVoucherAction::make(),
                    EditAction::make(),
                    \Filament\Actions\Action::make('printVoucher')
                        ->label(__('Print Voucher'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (\App\Models\CashDisbursement $record) => route('cash-disbursement.print-voucher', $record->id))
                        ->openUrlInNewTab(),
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
