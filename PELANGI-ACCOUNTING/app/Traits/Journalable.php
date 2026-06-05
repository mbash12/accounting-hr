<?php

namespace App\Traits;

use App\Services\JournalService;
use Filament\Notifications\Notification;

trait Journalable
{
    /**
     * Boot trait
     */
    protected static function bootJournalable()
    {
        // Create/update draft journal entry when document is saved
        static::saved(function ($model) {
            try {
                // Skip posted documents — journal already created or will be posted from Posting Center
                if (property_exists($model, 'status') && $model->status === 'posted') {
                    return;
                }

                $journalService = app(JournalService::class);
                $journalService->createJournalEntryFromDocument(
                    $model->getDocumentType(),
                    $model,
                    $model->getJournalEntryDescription()
                );
            } catch (\Exception $e) {
                \Log::error('Journal Entry Error: ' . $e->getMessage(), [
                    'model' => get_class($model),
                    'model_id' => $model->id ?? null,
                    'trace' => $e->getTraceAsString(),
                ]);
                Notification::make()
                    ->danger()
                    ->title('Journal Entry Error')
                    ->body('Failed to create journal entry: ' . $e->getMessage())
                    ->persistent()
                    ->send();
            }
        });

        // Delete journal entry when document is deleted
        static::deleted(function ($model) {
            try {
                $model->deleteJournalEntry();
            } catch (\Exception $e) {
                Notification::make()
                    ->danger()
                    ->title('Journal Entry Deletion Error')
                    ->body('Failed to delete journal entry: ' . $e->getMessage())
                    ->persistent()
                    ->send();
            }
        });
    }

    /**
     * Create journal entry for this document
     */
    public function createJournalEntry(): void
    {
        $journalService = app(JournalService::class);

        $journalService->createJournalEntryFromDocument(
            $this->getDocumentType(),
            $this,
            $this->getJournalEntryDescription()
        );
    }

    /**
     * Delete journal entry for this document
     */
    public function deleteJournalEntry(): void
    {
        $journalService = app(JournalService::class);

        $journalService->deleteJournalEntriesForDocument(
            $this->getDocumentType(),
            $this->id,
            $this->company_id
        );
    }

    /**
     * Regenerate journal entry for this document
     * Useful when user wants to recreate journal entries after adding account mappings
     */
    public function regenerateJournalEntry(): void
    {
        // Delete existing journal entry
        $this->deleteJournalEntry();

        // Create new journal entry
        $this->createJournalEntry();
    }

    /**
     * Check if journal entry already exists for this document
     */
    public function journalEntryExists(): bool
    {
        return \App\Models\JournalEntry::where('sub_module', $this->getDocumentType())
            ->where('reference_type', get_class($this))
            ->where('reference_id', $this->id)
            ->where('company_id', $this->company_id)
            ->exists();
    }

    /**
     * Check if journal entry is complete (all mappings configured)
     */
    public function isJournalEntryComplete(): bool
    {
        $mappings = \App\Models\AccountMapping::getMappingsForDocument(
            $this->getDocumentType(),
            $this->company_id
        );

        if ($mappings->isEmpty()) {
            return false;
        }

        // Get required mapping types for this document
        $documentMappings = \App\Models\AccountMapping::DOCUMENT_MAPPING_TYPES[$this->getDocumentType()] ?? [];

        // Check if all required mappings are configured
        foreach ($documentMappings as $mappingType) {
            if (!$mappings->has($mappingType)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing account mappings for this document
     */
    public function getMissingAccountMappings(): array
    {
        $configuredMappings = \App\Models\AccountMapping::getMappingsForDocument(
            $this->getDocumentType(),
            $this->company_id
        )->keys()->toArray();

        $documentMappings = \App\Models\AccountMapping::DOCUMENT_MAPPING_TYPES[$this->getDocumentType()] ?? [];

        $missing = [];
        foreach ($documentMappings as $mappingType) {
            if (!in_array($mappingType, $configuredMappings)) {
                $missing[] = $mappingType;
            }
        }

        return $missing;
    }

    /**
     * Get document type for journal entries
     */
    abstract protected function getDocumentType(): string;

    /**
     * Get description for journal entry
     */
    protected function getJournalEntryDescription(): string
    {
        $documentType = $this->getDocumentType();
        $documentNumber = $this->getDocumentNumber();

        return "{$documentType}: {$documentNumber}";
    }

    /**
     * Get document number
     */
    protected function getDocumentNumber(): string
    {
        // Try common field names
        $fields = [
            'invoice_number',
            'order_number',
            'delivery_number',
            'receipt_number',
            'return_number',
            'reference_no',
        ];

        foreach ($fields as $field) {
            if (isset($this->{$field})) {
                return $this->{$field};
            }
        }

        return '#' . $this->id;
    }
}
