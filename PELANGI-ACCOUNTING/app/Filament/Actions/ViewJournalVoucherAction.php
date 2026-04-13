<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class ViewJournalVoucherAction
{
    public static function make(): Action
    {
        return Action::make('viewJournalVoucher')
            ->label(__('View Journal Voucher'))
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->modalHeading(__('Voucher Jurnal'))
            ->modalWidth('6xl')
            ->modalContent(function ($record) {
                $journalEntry = $record instanceof \App\Models\JournalEntry ? $record : $record->journalEntry;
                
                if (!$journalEntry) {
                    return view('filament.actions.no-journal-voucher', [
                        'message' => __('No journal voucher found for this transaction. Journal voucher will be created when the transaction is posted.')
                    ]);
                }

                $journalEntry->load([
                    'items.account', 
                    'items.costCenter', 
                    'department', 
                    'company', 
                    'postedByUser',
                    'createdByUser'
                ]);

                return view('filament.actions.journal-voucher-detail', [
                    'journalEntry' => $journalEntry
                ]);
            })
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('Tutup'))
            ->disabled(fn($record) => !($record instanceof \App\Models\JournalEntry) && !$record->journalEntry);
    }
}

