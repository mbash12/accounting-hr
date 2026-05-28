<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Models\JournalEntryItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Account;
use Filament\Actions\Action;
use App\Filament\Actions\ViewJournalVoucherAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Actions\StaticAction;
use Illuminate\Support\HtmlString;
use UnitEnum;

class BukuBesarResource extends Resource
{
    protected static ?string $model = JournalEntryItem::class;

    protected static UnitEnum|string|null $navigationGroup = 'General Ledger';

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public static function getNavigationLabel(): string
    {
        return __('General Ledger');
    }

    public static function getPluralModelLabel(): string
    {
        return __('General Ledger');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('journalEntry.entry_number')
                    ->label(__('Reference No.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('journalEntry.date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('account.name')
                    ->label(__('Account'))
                    ->description(fn (JournalEntryItem $record): string => $record->account?->code ?? '')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact.name')
                    ->label(__('Contact'))
                    ->placeholder('-'),
                TextColumn::make('notes')
                    ->label(__('Description'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('debit')
                    ->label(__('Debit'))
                    ->money('IDR')
                    ->alignment(Alignment::End),
                TextColumn::make('credit')
                    ->label(__('Credit'))
                    ->money('IDR')
                    ->alignment(Alignment::End),
            ])
            ->filters([
                Filter::make('account_id')
                    ->form([
                        Select::make('account_id')
                            ->label(__('Account'))
                            ->placeholder(__('Select Account'))
                            ->options(function () {
                                $companyId = session('selected_company_id');
                                $accounts = Account::where('is_header', false)
                                    ->when($companyId && $companyId !== 'all', function ($query) use ($companyId) {
                                        $query->where('company_id', $companyId);
                                    })
                                    ->get()
                                    ->mapWithKeys(fn($a) => [$a->id => "{$a->code} - {$a->name}"]);
                                
                                return ['all' => __('All')] + $accounts->toArray();
                            })
                            ->searchable()
                            ->nullable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['account_id'] ?? null,
                            function (Builder $query, $accountId): Builder {
                                if ($accountId === 'all') {
                                    return $query;
                                }
                                return $query->where('account_id', $accountId);
                            }
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['account_id']) {
                            return null;
                        }
                        if ($data['account_id'] === 'all') {
                            return __('Account') . ': ' . __('All');
                        }
                        $account = Account::find($data['account_id']);
                        return __('Account') . ': ' . ($account ? "{$account->code} - {$account->name}" : $data['account_id']);
                    }),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label(__('From'))->default(now()->startOfMonth()),
                        DatePicker::make('until')->label(__('Until'))->default(now()->endOfMonth()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereHas('journalEntry', fn ($q) => $q->whereDate('date', '>=', $date)),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereHas('journalEntry', fn ($q) => $q->whereDate('date', '<=', $date)),
                            );
                    })
            ])
            ->persistFiltersInSession()
            ->modifyQueryUsing(function (Builder $query, $livewire) {
                $filterData = $livewire->tableFilters;
                $accountId = $filterData['account_id']['account_id'] ?? null;
                $companyId = $filterData['account_id']['company_id'] ?? session('selected_company_id');

                if (!$accountId) {
                }

                $obRecord = \App\Models\OpeningBalance::where('account_id', $accountId)
                    ->when($companyId && $companyId !== 'all', fn($q) => $q->where('company_id', $companyId))
                    ->first();
                
                if ($obRecord) {
                    $query->whereHas('journalEntry', fn($q) => $q->whereDate('date', '>=', $obRecord->date));
                }
            })
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    Action::make('edit')
                        ->label(__('Edit'))
                        ->icon('heroicon-o-pencil')
                        ->url(fn (JournalEntryItem $record): string => static::resolveSourceUrl($record, 'edit')),
                    Action::make('view')
                        ->label(__('Details'))
                        ->icon('heroicon-o-eye')
                        ->url(fn (JournalEntryItem $record): string => static::resolveSourceUrl($record, 'view')),
                    ViewJournalVoucherAction::make()
                        ->label(__('Journal Voucher')),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function resolveSourceUrl(JournalEntryItem $record, string $type = 'edit'): string
    {
        $journalEntry = $record->journalEntry;
        if (!$journalEntry) {
            return '#';
        }

        $referenceType = $journalEntry->reference_type;
        $referenceId = $journalEntry->reference_id;

        $resourceMap = [
            'App\Models\SalesInvoice' => \App\Filament\Resources\SalesInvoices\SalesInvoiceResource::class,
            'App\Services\SalesInvoice' => \App\Filament\Resources\SalesInvoices\SalesInvoiceResource::class,
            'App\Models\PayablePayment' => \App\Filament\Resources\PayablePayments\PayablePaymentResource::class,
            'App\Models\ReceivablePayment' => \App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource::class,
            'App\Models\CashReceipt' => \App\Filament\Resources\CashReceipts\CashReceiptResource::class,
            'App\Models\CashDisbursement' => \App\Filament\Resources\CashDisbursements\CashDisbursementResource::class,
            'App\Models\CashTransfer' => \App\Filament\Resources\CashTransfers\CashTransferResource::class,
            'App\Models\PurchaseInvoice' => \App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource::class,
            'App\Models\PurchaseOrder' => \App\Filament\Resources\PurchaseOrders\PurchaseOrderResource::class,
            'App\Models\SalesOrder' => \App\Filament\Resources\SalesOrders\SalesOrderResource::class,
            'App\Models\GoodsReceipt' => \App\Filament\Resources\GoodsReceipts\GoodsReceiptResource::class,
            'App\Models\SalesDelivery' => \App\Filament\Resources\SalesDeliveries\SalesDeliveryResource::class,
            'App\Models\PurchaseReturn' => \App\Filament\Resources\PurchaseReturns\PurchaseReturnResource::class,
            'App\Models\SalesReturn' => \App\Filament\Resources\SalesReturns\SalesReturnResource::class,
            'App\Models\AdvanceReceipt' => \App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource::class,
            'App\Models\AdvanceDisbursement' => \App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource::class,
        ];

        if ($referenceType && isset($resourceMap[$referenceType])) {
            $resource = $resourceMap[$referenceType];
            try {
                $page = ($type === 'view') ? 'view' : 'edit';
                return $resource::getUrl($page, ['record' => $referenceId]);
            } catch (\Exception $e) {
                try {
                    return $resource::getUrl('edit', ['record' => $referenceId]);
                } catch (\Exception $e2) {
                }
            }
        }

        return \App\Filament\Resources\JournalEntries\JournalEntryResource::getUrl('edit', ['record' => $journalEntry->id]);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->when(
                $selectedCompanyId && $selectedCompanyId !== 'all',
                fn (Builder $query) => $query->whereHas('journalEntry', function (Builder $q) use ($selectedCompanyId) {
                    $q->where('company_id', $selectedCompanyId);
                })
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBukuBesars::route('/'),
        ];
    }
}
