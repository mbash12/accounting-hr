<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

class RegenerateJournalEntry extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Regenerate Journal Entry')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Regenerate Journal Entry?')
            ->modalDescription('This will delete the existing journal entry and create a new one based on current account mappings. Continue?')
            ->action(fn ($record) => $this->regenerateJournalEntry($record))
            ->successNotificationTitle('Journal entry regenerated successfully')
            ->failureNotificationTitle('Failed to regenerate journal entry');
    }

    public function regenerateJournalEntry($record): void
    {
        if (!method_exists($record, 'regenerateJournalEntry')) {
            Notification::make()
                ->danger()
                ->title('Not Supported')
                ->body('This document does not support journal entry regeneration.')
                ->send();

            return;
        }

        try {
            $record->regenerateJournalEntry();

            Notification::make()
                ->success()
                ->title('Success')
                ->body('Journal entry has been regenerated successfully.')
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Failed to regenerate journal entry: ' . $e->getMessage())
                ->send();
        }
    }
}
