<?php

namespace App\Traits;

use App\Models\JournalEntry;
use Filament\Actions\Action;

/**
 * Trait for Edit pages to warn and unpost when editing a posted record.
 *
 * Usage: Add `use WarnEditPostedRecord;` to any EditRecord page
 * that needs to warn users before editing posted records.
 */
trait WarnEditPostedRecord
{
    /** @var bool|null Cached posted state (preserved after form fill changes status). */
    protected ?bool $wasRecordPosted = null;

    /**
     * Before form fill: if record is posted, temporarily set status to 'draft'
     * so the form's Select validation doesn't reject the 'posted' value.
     * The original posted state is cached in $wasRecordPosted.
     */
    protected function beforeFill(): void
    {
        if ($this->isRecordPosted()) {
            $this->wasRecordPosted = true;

            if (isset($this->record->status) && $this->record->status === 'posted') {
                $this->record->status = 'draft';
            }
        }
    }

    /**
     * Unpost the record's associated journal entry before saving.
     * (The record's own status was already set to 'draft' in beforeFill().)
     */
    protected function beforeSave(): void
    {
        if (!$this->isRecordPosted()) {
            return;
        }

        // Unpost the associated journal entry
        $this->unpostJournalEntry();

        // For JournalEntry model (standalone), unset posting fields
        if ($this->record instanceof JournalEntry) {
            $this->record->is_posted = false;
            $this->record->posted_by_user_id = null;
            $this->record->posted_at = null;
            $this->record->save();
        }
    }

    /**
     * Override save form action to show warning modal when record was posted.
     */
    protected function getSaveFormAction(): Action
    {
        if (!$this->isRecordPosted()) {
            return parent::getSaveFormAction();
        }

        return Action::make(parent::getSaveFormAction()->getName())
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->action(function () {
                $this->save();
            })
            ->modalHeading(__('⚠️ Data Sudah Diposting'))
            ->modalDescription(__(
                'Data ini sudah diposting dan memiliki jurnal yang sudah diposting. '
                . 'Jika Anda melanjutkan edit, status akan berubah menjadi DRAFT '
                . 'dan jurnal akan di-unpost. Anda perlu memposting ulang setelah selesai edit. '
                . 'Yakin ingin melanjutkan?'
            ))
            ->modalSubmitActionLabel(__('Unpost & Simpan'))
            ->keyBindings(['mod+s']);
    }

    /**
     * Check if the current record was in posted state.
     * Uses cached value if beforeFill() already ran, otherwise checks the record.
     */
    protected function isRecordPosted(): bool
    {
        if ($this->wasRecordPosted !== null) {
            return $this->wasRecordPosted;
        }

        if (!isset($this->record) || !$this->record->exists) {
            return false;
        }

        // JournalEntry uses is_posted boolean
        if (isset($this->record->is_posted) && $this->record->is_posted === true) {
            return true;
        }

        // Other documents use status = 'posted'
        if (isset($this->record->status) && $this->record->status === 'posted') {
            return true;
        }

        return false;
    }

    /**
     * Unpost the journal entry associated with this record.
     */
    protected function unpostJournalEntry(): void
    {
        // For JournalEntry model itself (standalone journal)
        if ($this->record instanceof JournalEntry) {
            return; // Handled directly in beforeSave()
        }

        // For models using Journalable trait (Sales, Purchase, etc.)
        $journalEntry = JournalEntry::where('reference_type', get_class($this->record))
            ->where('reference_id', $this->record->id)
            ->where('is_posted', true)
            ->first();

        if ($journalEntry) {
            $journalEntry->is_posted = false;
            $journalEntry->posted_by_user_id = null;
            $journalEntry->posted_at = null;
            $journalEntry->status = 'draft';
            $journalEntry->save();
        }
    }
}
