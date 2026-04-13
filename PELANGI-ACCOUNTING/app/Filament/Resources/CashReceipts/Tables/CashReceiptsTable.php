<?php

namespace App\Filament\Resources\CashReceipts\Tables;

use App\Models\Account;
use App\Models\BankAccount;
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

class CashReceiptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("receipt_number")
                    ->label(__("Receipt No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("toAccount.name")
                    ->label(__("Cash/Bank Account"))
                    ->formatStateUsing(function ($record) {
                        $account = null;
                        
                        if ($record->relationLoaded('toAccount') && $record->toAccount) {
                            $account = $record->toAccount;
                        }
                        
                        if (!$account && $record->to_account_id) {
                            $account = \App\Models\Account::withTrashed()->find($record->to_account_id);
                        }
                        
                        if ($account) {
                            $code = $account->code ?? '';
                            $name = $account->name ?? '';
                            $deleted = $account->trashed() ? ' (Deleted)' : '';
                            return $code . ' - ' . $name . $deleted;
                        }
                        
                        if ($record->to_account_id) {
                            return __('Account ID: :id (Not Found)', ['id' => $record->to_account_id]);
                        }
                        
                        return '-';
                    })
                    ->default('-')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('toAccount', function ($q) use ($search) {
                            $q->where('code', 'like', "%{$search}%")
                              ->orWhere('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('accounts', 'cash_receipts.to_account_id', '=', 'accounts.id')
                            ->orderBy('accounts.name', $direction)
                            ->select('cash_receipts.*');
                    }),
                TextColumn::make("total")
                    ->label(__("Total"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->badge()
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
                Filter::make('to_account_id')
                    ->form([
                        Select::make('to_account_id')
                            ->label(__('Bank Account'))
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $query = \App\Models\BankAccount::query()
                                    ->where('is_active', true);

                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $query->where(function ($q) use ($selectedCompanyId) {
                                        $q->where('company_id', $selectedCompanyId)
                                            ->orWhereNull('company_id'); 
                                    });
                                }

                                return $query->orderBy('account_name')
                                    ->limit(50)
                                    ->pluck('account_name', 'id');
                            })
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(fn($query, array $data) => $query->when($data['to_account_id'] ?? null, fn($q, $id) => $q->where('to_account_id', $id))),
                TrashedFilter::make(),
            ])
            ->defaultSort('date', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewJournalVoucherAction::make(),
                    EditAction::make(),
                    \Filament\Actions\Action::make('printVoucher')
                        ->label(__('Cetak Voucher'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (\App\Models\CashReceipt $record) => route('cash-receipt.print-voucher', $record->id))
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
