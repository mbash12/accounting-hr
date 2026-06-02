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

    public array $pendingMatches = [];

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
                        if (array_key_exists($record->id, $this->pendingMatches)) {
                            return $this->pendingMatches[$record->id];
                        }

                        if (! $record->suggested_invoice_id) {
                            return null;
                        }

                        $type = $record->suggested_invoice_type === SalesInvoice::class ? 'sales' : 'purchase';

                        return "{$type}:{$record->suggested_invoice_id}";
                    })
                    ->options(function (BankReconciliationItem $record) use ($openSalesInvoices, $openPurchaseInvoices): array {
                        $options = [];

                        if ($record->match_status === 'matched' && $record->suggested_invoice_id) {
                            $label = $this->formatInvoiceLabel($record);

                            return $record->suggested_invoice_id
                                ? ["{$label['type']}:{$record->suggested_invoice_id}" => $label['label']]
                                : [];
                        }

                        if ($record->suggested_invoice_id) {
                            $label = $this->formatInvoiceLabel($record);
                            $options["{$label['type']}:{$record->suggested_invoice_id}"] = $label['label'];
                        }

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
                    ->extraAttributes(fn (BankReconciliationItem $record) => [
                        'wire:key' => 'invoice-match-'.$record->id.'-'.($record->match_status ?? 'null'),
                    ])
                    ->disabled(fn (BankReconciliationItem $record): bool => $record->match_status === 'matched')
                    ->updateStateUsing(function ($state, BankReconciliationItem $record) {
                        $currentState = $record->suggested_invoice_id
                            ? (($record->suggested_invoice_type === SalesInvoice::class ? 'sales' : 'purchase') . ':' . $record->suggested_invoice_id)
                            : null;

                        if ($state === $currentState) {
                            unset($this->pendingMatches[$record->id]);
                        } else {
                            $this->pendingMatches[$record->id] = $state;
                        }

                        $this->resetTable();
                    }),
            ])
            ->recordActions([
                Action::make('confirm')
                    ->label(__('Confirm'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->size('sm')
                    ->visible(function (BankReconciliationItem $record): bool {
                        if (array_key_exists($record->id, $this->pendingMatches)) {
                            return false;
                        }

                        return $record->match_status === 'suggested';
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('Confirm Suggested Match'))
                    ->modalDescription(function (BankReconciliationItem $record) {
                        $label = $this->formatInvoiceLabel($record);

                        return __('Confirm matching to invoice :invoice?', ['invoice' => $label['label']]);
                    })
                    ->action(function (BankReconciliationItem $record) {
                        try {
                            app(BankReconciliationService::class)->confirmMatch($record);
                            $this->checkReconciliationComplete();
                            $this->resetTable();

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
            Action::make('save_matches')
                ->label(__('Save Matches'))
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->visible(fn () => filled($this->pendingMatches))
                ->requiresConfirmation()
                ->modalHeading(__('Save Matches'))
                ->modalDescription(__('Apply all selected invoice matches?'))
                ->action(function () {
                    $service = app(BankReconciliationService::class);
                    $applied = 0;
                    $failed = [];

                    foreach ($this->pendingMatches as $itemId => $pending) {
                        $record = BankReconciliationItem::find($itemId);
                        if (! $record) {
                            continue;
                        }

                        try {
                            if (blank($pending)) {
                                if ($record->match_status === 'suggested') {
                                    $service->unmatch($record);
                                }
                                continue;
                            }

                            [$type, $id] = explode(':', $pending);
                            $invoiceType = $type === 'sales' ? SalesInvoice::class : PurchaseInvoice::class;

                            $service->forceMatch($record, (int) $id, $invoiceType);
                            $applied++;
                        } catch (\Exception $e) {
                            $failed[] = $record->bank_description . ': ' . $e->getMessage();
                        }
                    }

                    $this->pendingMatches = [];
                    $this->checkReconciliationComplete();
                    $this->resetTable();

                    if (count($failed)) {
                        Notification::make()
                            ->warning()
                            ->title(__(':count applied, :failed failed.', [
                                'count' => $applied,
                                'failed' => count($failed),
                            ]))
                            ->body(implode("\n", $failed))
                            ->send();
                    } else {
                        Notification::make()
                            ->success()
                            ->title(__(':count match(es) saved.', ['count' => $applied]))
                            ->send();
                    }
                }),

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
                    $this->resetTable();

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
     * @return array{type: string, label: string}
     */
    private function formatInvoiceLabel(BankReconciliationItem $record): array
    {
        $id = $record->suggested_invoice_id;

        if (! $id) {
            return ['type' => '', 'label' => '—'];
        }

        $type = $record->suggested_invoice_type === SalesInvoice::class ? 'sales' : 'purchase';
        $amount = number_format((float) $record->suggested_invoice_amount, 2);

        if ($type === 'sales') {
            $invoice = SalesInvoice::with('customer')->find($id);
            $label = $invoice
                ? "{$invoice->invoice_number} — {$invoice->customer?->name} — Rp {$amount}"
                : "Inv #{$id} — Rp {$amount}";
        } else {
            $invoice = PurchaseInvoice::with('supplier')->find($id);
            $label = $invoice
                ? "{$invoice->invoice_number} — {$invoice->supplier?->name} — Rp {$amount}"
                : "Inv #{$id} — Rp {$amount}";
        }

        return ['type' => $type, 'label' => $label];
    }
}
