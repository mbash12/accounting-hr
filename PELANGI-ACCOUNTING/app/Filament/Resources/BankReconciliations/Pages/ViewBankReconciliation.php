<?php

namespace App\Filament\Resources\BankReconciliations\Pages;

use App\Filament\Resources\BankReconciliations\BankReconciliationResource;
use App\Models\BankReconciliationItem;
use App\Services\BankReconciliationService;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
        $record = $this->getRecord();
        $recalculate = fn () => app(BankReconciliationService::class)->getCalculatedAmounts($record);

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
                                    ->label(__('Bank Statement Total'))
                                    ->money('IDR')
                                    ->state(fn () => $recalculate()['statement_total']),
                                TextEntry::make('book_balance')
                                    ->label(__('Matched Amount'))
                                    ->money('IDR')
                                    ->state(fn () => $recalculate()['matched_amount']),
                                TextEntry::make('difference')
                                    ->label(__('Unmatched Amount'))
                                    ->money('IDR')
                                    ->state(fn () => $recalculate()['unmatched_amount'])
                                    ->color(fn () => $recalculate()['unmatched_amount'] > 0 ? 'danger' : 'success'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        $record = $this->getRecord();

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

                TextColumn::make('reference_no')
                    ->label(__('Reference'))
                    ->toggleable(),

                TextColumn::make('account_code')
                    ->label(__('Account Code'))
                    ->toggleable(),

                TextColumn::make('bank_debit')
                    ->label(__('Debit'))
                    ->money('IDR'),

                TextColumn::make('bank_credit')
                    ->label(__('Credit'))
                    ->money('IDR'),

                TextColumn::make('match_status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'matched' => __('Matched'),
                        'unmatched' => __('Unmatched'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'matched' => 'success',
                        'unmatched' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('bank_date', 'asc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save_matches')
                ->label(__('Save & Import Journals'))
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->visible(fn () => $this->getRecord()->status === 'in_progress')
                ->requiresConfirmation()
                ->modalHeading(__('Save & Import Journals'))
                ->modalDescription(__('Create journal entries for unmatched items with account code?'))
                ->action(function () {
                    $service = app(BankReconciliationService::class);
                    $result = $service->processMatches($this->getRecord());
                    $this->getRecord()->refresh();
                    $this->resetTable();

                    if (count($result['errors'])) {
                        Notification::make()
                            ->warning()
                            ->title(__(':count processed, :failed failed.', [
                                'count' => $result['processed'],
                                'failed' => count($result['errors']),
                            ]))
                            ->body(implode("\n", $result['errors']))
                            ->send();
                    } else {
                        Notification::make()
                            ->success()
                            ->title(__(':count journal(s) created.', ['count' => $result['processed']]))
                            ->send();
                    }
                }),
        ];
    }

}
