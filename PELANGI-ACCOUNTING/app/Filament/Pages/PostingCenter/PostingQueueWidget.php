<?php

namespace App\Filament\Pages\PostingCenter;

use App\Models\PostingQueue;
use App\Services\CashBankService;
use App\Services\JournalService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostingQueueWidget extends TableWidget
{
    protected static ?string $heading = 'Pending Transactions';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PostingQueue::query()
                    ->when(
                        session('selected_company_id') && session('selected_company_id') !== 'all',
                        fn ($q) => $q->where('company_id', session('selected_company_id'))
                    )
            )
            ->columns([
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->formatStateUsing(fn ($record) => $record->getTypeLabel())
                    ->badge()
                    ->color(fn ($record) => match ($record->type) {
                        'journal_entry' => 'info',
                        'cash_disbursement', 'cash_receipt', 'cash_transfer' => 'warning',
                        'sales_order', 'sales_invoice', 'sales_return' => 'success',
                        'purchase_order', 'goods_receipt', 'purchase_invoice', 'purchase_return' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label(__('Number'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('reference_no')
                    ->label(__('Reference'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Action::make('open')
                    ->label(__('Open'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (PostingQueue $record) => $record->getResourceUrl())
                    ->openUrlInNewTab()
                    ->color('gray'),
                Action::make('post')
                    ->label(__('Post'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (PostingQueue $record) => $this->postRecord($record))
                    ->requiresConfirmation()
                    ->modalHeading(fn (PostingQueue $record) => __('Post :type', ['type' => $record->getTypeLabel()]))
                    ->modalDescription(__('Post this transaction? This will create journal entries in the general ledger.'))
                    ->modalSubmitActionLabel(__('Yes, post it')),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options([
                        'journal_entry' => __('Journal Entry'),
                        'cash_disbursement' => __('Cash Disbursement'),
                        'cash_receipt' => __('Cash Receipt'),
                        'cash_transfer' => __('Cash Transfer'),
                        'sales_order' => __('Sales Order'),
                        'sales_invoice' => __('Sales Invoice'),
                        'sales_return' => __('Sales Return'),
                        'purchase_order' => __('Purchase Order'),
                        'goods_receipt' => __('Goods Receipt'),
                        'purchase_invoice' => __('Purchase Invoice'),
                        'purchase_return' => __('Purchase Return'),
                    ]),
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->multiple()
                    ->preload()
                    ->options([
                        'draft' => __('Draft'),
                        'approved' => __('Approved'),
                        'rejected' => __('Rejected'),
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('post_all')
                    ->label(__('Post All'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(fn () => $this->postAll())
                    ->requiresConfirmation()
                    ->modalHeading(__('Post All Transactions'))
                    ->modalDescription(__('Post ALL displayed unposted transactions?'))
                    ->modalSubmitActionLabel(__('Yes, post all')),
            ])
            ->bulkActions([
                \Filament\Actions\BulkAction::make('post_selected')
                    ->label(__('Post Selected'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($records) => $this->postBulk($records))
                    ->requiresConfirmation()
                    ->modalHeading(__('Post Selected'))
                    ->modalDescription(__('Post the selected transactions?'))
                    ->modalSubmitActionLabel(__('Yes, post selected')),
            ])
            ->defaultSort('date', 'desc');
    }

    protected function postRecord(PostingQueue $record): void
    {
        $source = $record->getSourceModel();
        if (!$source) {
            Notification::make()->title(__('Source record not found.'))->danger()->send();
            return;
        }

        try {
            DB::transaction(function () use ($record, $source) {
                match ($record->type) {
                    'journal_entry' => $this->postJournalEntry($source),
                    'cash_disbursement', 'cash_receipt', 'cash_transfer' => $this->postCashRecord($source),
                    default => $this->postDocument($source),
                };
            });

            Notification::make()
                ->title(__(':type :number posted.', ['type' => $record->getTypeLabel(), 'number' => $record->document_number]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Posting Failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function postBulk($records): void
    {
        $success = 0;
        $fail = 0;

        foreach ($records as $record) {
            try {
                DB::transaction(function () use ($record) {
                    $source = $record->getSourceModel();
                    if (!$source) throw new \RuntimeException('Source not found');
                    match ($record->type) {
                        'journal_entry' => $this->postJournalEntry($source),
                        'cash_disbursement', 'cash_receipt', 'cash_transfer' => $this->postCashRecord($source),
                        default => $this->postDocument($source),
                    };
                });
                $success++;
            } catch (\Exception $e) {
                $fail++;
            }
        }

        $body = __(':success posted.', ['success' => $success]);
        if ($fail > 0) $body .= ' ' . __(':fail failed.', ['fail' => $fail]);

        Notification::make()
            ->title(__('Bulk Posting Complete'))
            ->body($body)
            ->color($fail > 0 ? 'warning' : 'success')
            ->send();
    }

    protected function postAll(): void
    {
        $this->postBulk($this->table->getQuery()->get());
    }

    protected function postJournalEntry($entry): void
    {
        $entry->update([
            'is_posted' => true,
            'status' => 'posted',
            'posted_by_user_id' => Auth::id(),
            'posted_at' => now(),
            'updated_by_user_id' => Auth::id(),
        ]);
    }

    protected function postCashRecord($record): void
    {
        $record->update(['status' => 'posted', 'updated_by_user_id' => Auth::id()]);
        $record->refresh();
        app(CashBankService::class)->createJournalEntryForRecord($record);
    }

    protected function postDocument($document): void
    {
        $document->update(['status' => 'posted', 'updated_by_user_id' => Auth::id()]);
        $document->refresh();
        app(JournalService::class)->createJournalEntryFromDocument(
            $document->getDocumentType(),
            $document,
            $document->getJournalEntryDescription()
        );
    }
}
