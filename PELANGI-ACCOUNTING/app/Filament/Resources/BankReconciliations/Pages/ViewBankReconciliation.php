<?php

namespace App\Filament\Resources\BankReconciliations\Pages;

use App\Filament\Resources\BankReconciliations\BankReconciliationResource;
use App\Models\BankReconciliationItem;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Services\BankReconciliationService;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ViewBankReconciliation extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BankReconciliationResource::class;

    protected string $view = 'filament.resources.bank-reconciliations.pages.view-bank-reconciliation';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Reconciliation Summary'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('statement_date')
                                    ->label(__('Statement Date'))
                                    ->date(),
                                TextEntry::make('bankAccount.account_name')
                                    ->label(__('Bank Account'))
                                    ->formatStateUsing(fn ($record) => $record->bankAccount
                                        ? "{$record->bankAccount->account_number} - {$record->bankAccount->account_name}"
                                        : '—'),
                                TextEntry::make('status')
                                    ->label(__('Status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'in_progress' => 'warning',
                                        'pending' => 'gray',
                                        'failed' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('statement_balance')
                                    ->label(__('Statement Balance'))
                                    ->money('IDR'),
                                TextEntry::make('book_balance')
                                    ->label(__('Book Balance'))
                                    ->money('IDR'),
                                TextEntry::make('difference')
                                    ->label(__('Difference'))
                                    ->money('IDR'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        $record = $this->getRecord();
        $companyId = $record->company_id;

        $openSalesInvoices = SalesInvoice::query()
            ->where('company_id', $companyId)
            ->where('outstanding_amount', '>', 0)
            ->with('customer')
            ->get()
            ->keyBy('id');

        $openPurchaseInvoices = PurchaseInvoice::query()
            ->where('company_id', $companyId)
            ->where('outstanding_amount', '>', 0)
            ->with('supplier')
            ->get()
            ->keyBy('id');

        return $table
            ->query(
                BankReconciliationItem::query()
                    ->where('bank_reconciliation_id', $record->id)
            )
            ->columns([
                TextColumn::make('bank_date')
                    ->label(__('Date'))
                    ->date(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'incoming' => __('Incoming'),
                        'outgoing' => __('Outgoing'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'incoming' => 'success',
                        'outgoing' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('bank_description')
                    ->label(__('Description'))
                    ->wrap(),

                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('IDR')
                    ->state(fn (BankReconciliationItem $record): float => (float) ($record->bank_debit > 0 ? $record->bank_debit : $record->bank_credit)),

                TextColumn::make('match_status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'matched' => __('Matched'),
                        'suggested' => __('Suggested'),
                        'unmatched' => __('Unmatched'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'matched' => 'success',
                        'suggested' => 'warning',
                        'unmatched' => 'danger',
                        default => 'gray',
                    }),

                SelectColumn::make('invoice_match')
                    ->label(__('Invoice'))
                    ->placeholder(__('—'))
                    ->state(function (BankReconciliationItem $record): ?string {
                        if (! $record->suggested_invoice_id) {
                            return null;
                        }

                        $type = $record->suggested_invoice_type === SalesInvoice::class ? 'sales' : 'purchase';

                        return "{$type}:{$record->suggested_invoice_id}";
                    })
                    ->options(function (BankReconciliationItem $record) use ($openSalesInvoices, $openPurchaseInvoices): array {
                        $options = [];

                        if ($record->match_status === 'matched' && $record->suggested_invoice_id) {
                            $label = $this->formatInvoiceLabel($record, $openSalesInvoices, $openPurchaseInvoices);

                            return $record->suggested_invoice_id
                                ? ["{$label['type']}:{$record->suggested_invoice_id}" => $label['label']]
                                : [];
                        }

                        // Add currently suggested/selected invoice first
                        if ($record->suggested_invoice_id) {
                            $label = $this->formatInvoiceLabel($record, $openSalesInvoices, $openPurchaseInvoices);
                            $options["{$label['type']}:{$record->suggested_invoice_id}"] = $label['label'];
                        }

                        // Add available open invoices
                        if ($record->type === 'incoming') {
                            foreach ($openSalesInvoices as $id => $invoice) {
                                $key = "sales:{$id}";
                                if (! array_key_exists($key, $options)) {
                                    $options[$key] = sprintf('%s — %s — Rp %s',
                                        $invoice->invoice_number,
                                        $invoice->customer?->name ?? '—',
                                        number_format((float) $invoice->outstanding_amount, 2)
                                    );
                                }
                            }
                        } else {
                            foreach ($openPurchaseInvoices as $id => $invoice) {
                                $key = "purchase:{$id}";
                                if (! array_key_exists($key, $options)) {
                                    $options[$key] = sprintf('%s — %s — Rp %s',
                                        $invoice->invoice_number,
                                        $invoice->supplier?->name ?? '—',
                                        number_format((float) $invoice->outstanding_amount, 2)
                                    );
                                }
                            }
                        }

                        return $options;
                    })
                    ->searchableOptions()
                    ->native(true)
                    ->disabled(fn (BankReconciliationItem $record): bool => $record->match_status === 'matched')
                    ->updateStateUsing(function ($state, BankReconciliationItem $record) {
                        $service = app(BankReconciliationService::class);

                        try {
                            if (blank($state)) {
                                if ($record->match_status === 'suggested') {
                                    $service->unmatch($record);
                                }

                                $this->checkReconciliationComplete();
                                Notification::make()
                                    ->info()
                                    ->title(__('Match removed.'))
                                    ->send();

                                return;
                            }

                            [$type, $id] = explode(':', $state);
                            $invoiceType = $type === 'sales' ? SalesInvoice::class : PurchaseInvoice::class;

                            $service->forceMatch($record, (int) $id, $invoiceType);
                            $this->checkReconciliationComplete();

                            Notification::make()
                                ->success()
                                ->title(__('Matched successfully. Payment created.'))
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('Match failed'))
                                ->body($e->getMessage())
                                ->send();

                            throw $e;
                        }
                    }),
            ])
            ->recordActions([
                Action::make('confirm')
                    ->label(__('Confirm'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->size('sm')
                    ->visible(fn (BankReconciliationItem $record): bool => $record->match_status === 'suggested')
                    ->action(function (BankReconciliationItem $record) {
                        try {
                            app(BankReconciliationService::class)->confirmMatch($record);
                            $this->checkReconciliationComplete();

                            Notification::make()
                                ->success()
                                ->title(__('Match confirmed. Payment created.'))
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('Confirmation failed'))
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('bank_date', 'asc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirm_all_suggested')
                ->label(__('Confirm All Suggested'))
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => $this->getRecord()->items()->where('match_status', 'suggested')->exists())
                ->requiresConfirmation()
                ->action(function () {
                    $service = app(BankReconciliationService::class);
                    $confirmed = 0;

                    foreach ($this->getRecord()->items()->where('match_status', 'suggested')->get() as $item) {
                        try {
                            $service->confirmMatch($item);
                            $confirmed++;
                        } catch (\Exception $e) {
                            // skip
                        }
                    }

                    $this->checkReconciliationComplete();

                    Notification::make()
                        ->success()
                        ->title(__(':count confirmation(s) processed.', ['count' => $confirmed]))
                        ->send();
                }),
        ];
    }

    protected function checkReconciliationComplete(): void
    {
        $remaining = $this->getRecord()->items()
            ->whereIn('match_status', ['suggested', 'unmatched'])
            ->count();

        if ($remaining === 0) {
            $this->getRecord()->update([
                'status' => 'completed',
                'reconciled_at' => now(),
                'reconciled_by_user_id' => auth()->id(),
            ]);
        } elseif ($this->getRecord()->status === 'pending') {
            $this->getRecord()->update(['status' => 'in_progress']);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, SalesInvoice>  $openSalesInvoices
     * @param  \Illuminate\Support\Collection<int, PurchaseInvoice>  $openPurchaseInvoices
     * @return array{type: string, label: string}
     */
    private function formatInvoiceLabel(BankReconciliationItem $record, $openSalesInvoices, $openPurchaseInvoices): array
    {
        $id = $record->suggested_invoice_id;
        $type = $record->suggested_invoice_type === SalesInvoice::class ? 'sales' : 'purchase';
        $amount = number_format((float) $record->suggested_invoice_amount, 2);

        if ($type === 'sales') {
            $invoice = $openSalesInvoices->get($id);
            $label = $invoice
                ? "{$invoice->invoice_number} — {$invoice->customer?->name} — Rp {$amount}"
                : "Inv #{$id} — Rp {$amount}";
        } else {
            $invoice = $openPurchaseInvoices->get($id);
            $label = $invoice
                ? "{$invoice->invoice_number} — {$invoice->supplier?->name} — Rp {$amount}"
                : "Inv #{$id} — Rp {$amount}";
        }

        return ['type' => $type, 'label' => $label];
    }
}
