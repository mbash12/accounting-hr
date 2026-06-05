# Draft Journal Entry Workflow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** All operational documents auto-create draft journal entries on save. Posting Center becomes a pure "review and post draft journal entries" hub. No document-type-specific posting logic.

**Architecture:** Modify `JournalService`, `CashBankService`, and `ReceivablePayableService` to create draft journal entries on document save. Simplify `posting_queue` view to SELECT from `journal_entries WHERE is_posted = false`. Posting logic becomes: flip `is_posted=true` + update source document status.

**Tech Stack:** Laravel, Filament, PostgreSQL

---

## File Overview

| File | Change |
|------|--------|
| `app/Services/JournalService.php` | Create drafts instead of posted entries |
| `app/Traits/Journalable.php` | Auto-create drafts on every save (not just on 'posted') |
| `app/Services/CashBankService.php` | Create draft entries on save, delete on non-draft |
| `app/Services/ReceivablePayableService.php` | Create draft entries on save |
| `app/Filament/Pages/PostingCenter/PostingQueueWidget.php` | Simplify posting: just flip is_posted + update source status |
| `database/migrations/xxxx_xx_xx_simplify_posting_queue_view.php` | New migration: view = single SELECT from journal_entries |
| `app/Models/PostingQueue.php` | Simplify: remove per-type resource mapping, use JournalEntryResource |
| `app/Filament/Resources/ReceivablePayments/Pages/CreateReceivablePayment.php` | Remove journal entry creation from afterCreate |
| `app/Filament/Resources/PayablePayments/Pages/CreatePayablePayment.php` | Remove journal entry creation from afterCreate |

---

### Task 1: Modify JournalService to create draft entries

**Files:**
- Modify: `app/Services/JournalService.php:78-108` (createNewJournalEntry)

- [ ] **Step 1: Change createNewJournalEntry to create drafts**

In `JournalService::createNewJournalEntry()`, change:
```php
$journalEntry = JournalEntry::create([
    'entry_number' => $this->generateEntryNumber(),
    'date' => $document->date ?? now(),
    'reference_no' => $document->reference_no ?? null,
    'description' => $description,
    'amount' => 0,
    'total_amount' => $document->total_amount ?? 0,
    'status' => 'draft',
    'is_posted' => false,
    'sub_module' => $documentType,
    'reference_type' => get_class($document),
    'reference_id' => $document->id,
    'company_id' => $document->company_id,
    'created_by_user_id' => Auth::id(),
    'updated_by_user_id' => Auth::id(),
]);
```

Remove `'posted_by_user_id' => Auth::id()` and `'posted_at' => now()` from the create array (only set on post).

- [ ] **Step 2: Commit**

```bash
git add app/Services/JournalService.php
git commit -m "feat: JournalService creates draft journal entries instead of posted"
```

---

### Task 2: Modify Journalable trait to auto-create drafts on save

**Files:**
- Modify: `app/Traits/Journalable.php` (bootJournalable method)

- [ ] **Step 1: Rewrite bootJournalable to create drafts on every save**

Replace the entire `bootJournalable()` method:

```php
protected static function bootJournalable()
{
    // Create/update draft journal entry when document is saved
    static::saved(function ($model) {
        try {
            if (property_exists($model, 'status')) {
                // Skip orders - no accounting impact
                if (in_array($model->getDocumentType(), ['sales_order', 'purchase_order'])) {
                    return;
                }

                // Skip posted documents - journal already created or will be posted from Posting Center
                if ($model->status === 'posted') {
                    return;
                }

                // For all other statuses (draft, etc.): create/update draft journal entry
                $journalService = app(JournalService::class);
                $journalService->createJournalEntryFromDocument(
                    $model->getDocumentType(),
                    $model,
                    $model->getJournalEntryDescription()
                );
            } else {
                // For models without status field, create journal entry
                $journalService = app(JournalService::class);
                $journalService->createJournalEntryFromDocument(
                    $model->getDocumentType(),
                    $model,
                    $model->getJournalEntryDescription()
                );
            }
        } catch (\Exception $e) {
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
```

- [ ] **Step 2: Commit**

```bash
git add app/Traits/Journalable.php
git commit -m "feat: Journalable trait auto-creates draft journal entries on document save"
```

---

### Task 3: Modify CashBankService to create draft entries

**Files:**
- Modify: `app/Services/CashBankService.php` (createJournalEntryForRecord, createJournalEntryWithItems)

- [ ] **Step 1: Change createJournalEntryForRecord to always create draft**

Replace the `createJournalEntryForRecord` method:

```php
public function createJournalEntryForRecord($record): void
{
    $existingJournalEntry = JournalEntry::where('reference_type', get_class($record))
        ->where('reference_id', $record->id)
        ->first();

    $departmentId = $existingJournalEntry?->department_id ?? 1;
    $costCenterId = 1;
    if ($existingJournalEntry) {
        $firstItem = $existingJournalEntry->items()->first();
        if ($firstItem) {
            $costCenterId = $firstItem->cost_center_id ?? 1;
        }
        $existingJournalEntry->items()->delete();
        $existingJournalEntry->delete();
    }

    if ($record instanceof CashReceipt) {
        $this->createJournalEntryForCashReceipt($record, $departmentId, $costCenterId);
    } elseif ($record instanceof CashDisbursement) {
        $this->createJournalEntryForCashDisbursement($record, $departmentId, $costCenterId);
    } elseif ($record instanceof CashTransfer) {
        $this->createJournalEntryForCashTransfer($record, $departmentId, $costCenterId);
    }
}
```

Key change: Remove the `if (!$isPosted) { delete and return; }` guard. Now always creates (rebuilds) the journal entry.

- [ ] **Step 2: Change all three create methods to pass draft status**

In `createJournalEntryForCashReceipt`, `createJournalEntryForCashDisbursement`, and `createJournalEntryForCashTransfer`, change the `'status' => 'posted'` in the header array to `'status' => 'draft'`.

Example for `createJournalEntryForCashReceipt`:
```php
$this->createJournalEntryWithItems([
    // ... other fields ...
    'status' => 'draft',  // was 'posted'
    // ... other fields ...
], $journalItems);
```

Apply the same change to all three methods.

- [ ] **Step 3: Commit**

```bash
git add app/Services/CashBankService.php
git commit -m "feat: CashBankService creates draft journal entries on save"
```

---

### Task 4: Modify ReceivablePayableService to create draft entries

**Files:**
- Modify: `app/Services/ReceivablePayableService.php` (createJournalEntryForReceivablePayment, createJournalEntryForPayablePayment)

- [ ] **Step 1: Change createJournalEntryForReceivablePayment to create draft**

In the `JournalEntry::create()` call within `createJournalEntryForReceivablePayment`, change:
```php
'status' => 'draft',      // was 'posted'
'is_posted' => false,     // was true
// Remove: 'posted_by_user_id' => Auth::id(),
// Remove: 'posted_at' => now(),
```

- [ ] **Step 2: Change createJournalEntryForPayablePayment to create draft**

Same change in `createJournalEntryForPayablePayment`:
```php
'status' => 'draft',      // was 'posted'
'is_posted' => false,     // was true
// Remove: 'posted_by_user_id' => Auth::id(),
// Remove: 'posted_at' => now(),
```

- [ ] **Step 3: Commit**

```bash
git add app/Services/ReceivablePayableService.php
git commit -m "feat: ReceivablePayableService creates draft journal entries on save"
```

---

### Task 5: Remove journal creation from CreateReceivablePayment/CreatePayablePayment

**Files:**
- Modify: `app/Filament/Resources/ReceivablePayments/Pages/CreateReceivablePayment.php` (afterCreate)
- Modify: `app/Filament/Resources/PayablePayments/Pages/CreatePayablePayment.php` (afterCreate)

- [ ] **Step 1: Remove journal entry update from CreateReceivablePayment afterCreate**

In `CreateReceivablePayment::afterCreate()`, the `$service->updateOutstandingJournalEntryForSalesInvoice($invoice)` call should remain (it updates the invoice's outstanding entry, not the payment's entry). But verify that `createJournalEntryForReceivablePayment` is NOT called here. If it is, remove it.

Current code in afterCreate (lines 165-173):
```php
try {
    $service = app(ReceivablePayableService::class);
    $service->updateOutstandingJournalEntryForSalesInvoice($invoice);
} catch (\Exception $e) {
    // ...
}
```

This is correct - it updates the sales invoice's outstanding journal entry, not the payment's entry. No change needed here.

- [ ] **Step 2: Same verification for CreatePayablePayment**

Verify that `createJournalEntryForPayablePayment` is NOT called in `CreatePayablePayment::afterCreate()`. The `updateOutstandingJournalEntryForPurchaseInvoice` call should remain.

- [ ] **Step 3: Commit (if any changes made)**

```bash
git add app/Filament/Resources/ReceivablePayments/Pages/CreateReceivablePayment.php app/Filament/Resources/PayablePayments/Pages/CreatePayablePayment.php
git commit -m "chore: verify no duplicate journal creation in payment create pages"
```

---

### Task 6: Simplify posting_queue view to show only journal entries

**Files:**
- Create: `database/migrations/2026_06_05_090000_simplify_posting_queue_to_journal_entries.php`

- [ ] **Step 1: Create migration to simplify the view**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        DB::statement("
            CREATE OR REPLACE VIEW posting_queue AS
            SELECT
                COALESCE(sub_module, 'journal_entry')::text AS type,
                entry_number::text AS document_number,
                date,
                COALESCE(reference_no, '')::text AS reference_no,
                COALESCE(description, '')::text AS description,
                amount::numeric(20,2) AS amount,
                status::text,
                id AS source_id,
                'App\\\\Models\\\\JournalEntry'::text AS source_type,
                company_id,
                created_at,
                updated_at
            FROM journal_entries
            WHERE is_posted = false
              AND deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        // Restore previous view with all document types
        // (copy from the previous migration's up() method)
    }
};
```

- [ ] **Step 2: Run migration**

```bash
php artisan migrate
```

- [ ] **Step 3: Verify view works**

```bash
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$queues = DB::table('posting_queue')->get();
echo 'Total: ' . \$queues->count() . PHP_EOL;
echo 'Types: ' . \$queues->pluck('type')->unique()->implode(', ') . PHP_EOL;
"
```

- [ ] **Step 4: Commit**

```bash
git add database/migrations/2026_06_05_090000_simplify_posting_queue_to_journal_entries.php
git commit -m "feat: simplify posting_queue view to show only draft journal entries"
```

---

### Task 7: Simplify PostingQueueWidget posting logic

**Files:**
- Modify: `app/Filament/Pages/PostingCenter/PostingQueueWidget.php`

- [ ] **Step 1: Replace postRecord match block**

Replace the `postRecord()` method:

```php
protected function postRecord(PostingQueue $record): void
{
    $source = $record->getSourceModel();
    if (!$source) {
        Notification::make()->title(__('Source record not found.'))->danger()->send();
        return;
    }

    try {
        DB::transaction(function () use ($record, $source) {
            // 1. Post the journal entry
            $source->update([
                'is_posted' => true,
                'status' => 'posted',
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'updated_by_user_id' => Auth::id(),
            ]);

            // 2. Update source document status
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

protected function updateSourceDocumentStatus(JournalEntry $journalEntry): void
{
    if (!$journalEntry->reference_type || !$journalEntry->reference_id) {
        return; // Manual journal entry, no source document
    }

    $sourceClass = $journalEntry->reference_type;
    if (!class_exists($sourceClass)) {
        return;
    }

    $source = $sourceClass::find($journalEntry->reference_id);
    if (!$source) {
        return;
    }

    // Determine the posted status based on source type
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
```

- [ ] **Step 2: Replace postBulk match block**

Replace the `postBulk()` method:

```php
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
```

- [ ] **Step 3: Remove old postCashRecord, postJournalEntry, postReceivablePayment, postPayablePayment, postDocument methods**

These are no longer needed. Remove them.

- [ ] **Step 4: Update filter options**

Since all items are now journal entries, simplify the type filter:

```php
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
        'receivable_payment' => __('Receivable Payment'),
        'payable_payment' => __('Payable Payment'),
        'sales_invoice' => __('Sales Invoice'),
        'sales_return' => __('Sales Return'),
        'goods_receipt' => __('Goods Receipt'),
        'purchase_invoice' => __('Purchase Invoice'),
        'purchase_return' => __('Purchase Return'),
    ]),
```

- [ ] **Step 5: Update status filter options**

Journal entries use 'draft' and 'posted' status. Update:

```php
SelectFilter::make('status')
    ->label(__('Status'))
    ->multiple()
    ->preload()
    ->options([
        'draft' => __('Draft'),
        'posted' => __('Posted'),
    ]),
```

- [ ] **Step 6: Add import for JournalEntry model**

Add at top of file:
```php
use App\Models\JournalEntry;
```

- [ ] **Step 7: Commit**

```bash
git add app/Filament/Pages/PostingCenter/PostingQueueWidget.php
git commit -m "feat: simplify PostingQueueWidget to post journal entries and update source status"
```

---

### Task 8: Simplify PostingQueue model

**Files:**
- Modify: `app/Models/PostingQueue.php`

- [ ] **Step 1: Simplify typeResourceMap**

All items are now journal entries. The resource URL should point to JournalEntryResource:

```php
protected static array $typeResourceMap = [
    'journal_entry' => JournalEntryResource::class,
    'cash_disbursement' => JournalEntryResource::class,
    'cash_receipt' => JournalEntryResource::class,
    'cash_transfer' => JournalEntryResource::class,
    'receivable_payment' => JournalEntryResource::class,
    'payable_payment' => JournalEntryResource::class,
    'sales_invoice' => JournalEntryResource::class,
    'sales_return' => JournalEntryResource::class,
    'goods_receipt' => JournalEntryResource::class,
    'purchase_invoice' => JournalEntryResource::class,
    'purchase_return' => JournalEntryResource::class,
];
```

Or simplify to a single default since all are JournalEntryResource.

- [ ] **Step 2: Simplify getResourceUrl**

```php
public function getResourceUrl(): ?string
{
    return JournalEntryResource::getUrl('edit', ['record' => $this->source_id]);
}
```

- [ ] **Step 3: Remove unused resource imports**

Remove all resource imports except `JournalEntryResource`.

- [ ] **Step 4: Commit**

```bash
git add app/Models/PostingQueue.php
git commit -m "feat: simplify PostingQueue model - all items are journal entries"
```

---

### Task 9: Verify and test

- [ ] **Step 1: Create a test Sales Invoice (draft)**

Verify a draft journal entry is created automatically.

```bash
php artisan tinker --execute="
use App\Models\SalesInvoice;
\$inv = SalesInvoice::first();
echo 'Invoice status: ' . \$inv->status . PHP_EOL;
echo 'Journal entries: ' . \$inv->journalEntries()->count() . PHP_EOL;
\$je = \$inv->journalEntries()->first();
if(\$je) {
    echo 'JE status: ' . \$je->status . PHP_EOL;
    echo 'JE is_posted: ' . (\$je->is_posted ? 'true' : 'false') . PHP_EOL;
    echo 'JE items: ' . \$je->items()->count() . PHP_EOL;
}
"
```

- [ ] **Step 2: Verify posting queue shows draft entries**

```bash
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$queues = DB::table('posting_queue')->get();
echo 'Total: ' . \$queues->count() . PHP_EOL;
foreach(\$queues->take(5) as \$q) {
    echo \$q->type . ' | ' . \$q->document_number . ' | ' . \$q->status . PHP_EOL;
}
"
```

- [ ] **Step 3: Test posting from Posting Center**

Post one journal entry from the UI and verify:
1. `is_posted` changes to `true`
2. Source document status changes to 'posted'/'completed'
3. Entry disappears from posting queue

- [ ] **Step 4: Test Cash Receipt draft creation**

Create a Cash Receipt and verify draft journal entry is created.

- [ ] **Step 5: Test Receivable Payment draft creation**

Create a Receivable Payment and verify draft journal entry is created.

- [ ] **Step 6: Run lint/typecheck if available**

```bash
php artisan migrate:status
```
