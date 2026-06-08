<?php

namespace App\Filament\Pages\PostingCenter;

use App\Models\JournalEntry;
use App\Models\PostingQueue;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                        'receivable_payment', 'payable_payment' => 'info',
                        'sales_invoice', 'sales_return' => 'success',
                        'goods_receipt', 'purchase_invoice', 'purchase_return' => 'danger',
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
                    ->formatStateUsing(fn (string $state): string => \Illuminate\Support\Str::headline($state))
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
            ->filters([], layout: FiltersLayout::AboveContent)
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
                $source->update([
                    'is_posted' => true,
                    'status' => 'posted',
                    'posted_by_user_id' => Auth::id(),
                    'posted_at' => now(),
                    'updated_by_user_id' => Auth::id(),
                ]);

                $this->updateSourceDocumentStatus($source);
            });

            Notification::make()
                ->title(__(':type :number posted.', ['type' => $record->getTypeLabel(), 'number' => $record->document_number]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error("Posting failed for {$record->type} #{$record->document_number}: " . $e->getMessage(), [
                'exception' => $e,
                'record_id' => $record->source_id,
                'record_type' => $record->source_type,
            ]);

            Notification::make()
                ->title(__('Posting Failed'))
                ->body(__(':type :number failed: :error', [
                    'type' => $record->getTypeLabel(),
                    'number' => $record->document_number,
                    'error' => $e->getMessage(),
                ]))
                ->danger()
                ->persistent()
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

                    $source->update([
                        'is_posted' => true,
                        'status' => 'posted',
                        'posted_by_user_id' => Auth::id(),
                        'posted_at' => now(),
                        'updated_by_user_id' => Auth::id(),
                    ]);

                    $this->updateSourceDocumentStatus($source);
                });
                $success++;
            } catch (\Exception $e) {
                Log::error("Bulk posting failed for {$record->type} #{$record->document_number}: " . $e->getMessage(), [
                    'exception' => $e,
                    'record_id' => $record->source_id,
                    'record_type' => $record->source_type,
                ]);
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

    protected function updateSourceDocumentStatus(JournalEntry $journalEntry): void
    {
        if (!$journalEntry->reference_type || !$journalEntry->reference_id) {
            return;
        }

        $sourceClass = $journalEntry->reference_type;
        if (!class_exists($sourceClass)) {
            return;
        }

        $source = $sourceClass::find($journalEntry->reference_id);
        if (!$source) {
            return;
        }

        $postedStatus = match (true) {
            $source instanceof \App\Models\ReceivablePayment => 'completed',
            $source instanceof \App\Models\PayablePayment => 'completed',
            default => 'posted',
        };

        $source->update([
            'status' => $postedStatus,
            'updated_by_user_id' => Auth::id(),
        ]);
    }
}
