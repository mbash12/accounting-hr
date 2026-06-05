# Remove SalesOrder Status Display and Constraints

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove SalesOrder status UI and all status-based constraints that prevent creating invoices.

**Architecture:** Remove status column, form field, bulk action, and visibility checks. Keep the `status` column in the database model (no model changes).

**Tech Stack:** Laravel, Filament

---

## File Overview

| File | Change |
|------|--------|
| `app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php` | Remove status column, Create Invoice status constraint, RegenerateJournalEntry status constraint, Change Status bulk action |
| `app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php` | Remove status Select field |
| `app/Filament/Resources/SalesInvoices/Schemas/SalesInvoiceForm.php` | Remove `where('status', 'posted')` from SO dropdown |

---

### Task 1: Remove status display from SalesOrder table

**Files:**
- Modify: `app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php`

- [ ] **Step 1: Remove status badge column (lines 65-83)**

Delete the entire `TextColumn::make("status")` block:
```php
TextColumn::make("status")
    ->label("Status")
    ->searchable()
    ->badge()
    ->toggleable(isToggledHiddenByDefault: false)
    ->formatStateUsing(
        fn(string $state): string => match ($state) {
            "draft" => "Draft",
            "posted" => "Posted",
            default => $state,
        },
    )
    ->color(
        fn(string $state): string => match ($state) {
            "draft" => "gray",
            "posted" => "success",
            default => "gray",
        },
    ),
```

- [ ] **Step 2: Remove status constraint from "Create Invoice" action (line 201)**

Change:
```php
->visible(function (SalesOrder $record): bool {
    $meta = $record->invoice_meta ?: $record->computeInvoiceMeta();
    return (float) ($meta['remaining'] ?? 0) > 0 && $record->status === 'posted';
})
```

To:
```php
->visible(function (SalesOrder $record): bool {
    $meta = $record->invoice_meta ?: $record->computeInvoiceMeta();
    return (float) ($meta['remaining'] ?? 0) > 0;
})
```

- [ ] **Step 3: Remove status constraint from RegenerateJournalEntry (line 292)**

Change:
```php
RegenerateJournalEntry::make('regenerateJournalEntry')
    ->visible(fn ($record) => $record->status !== 'draft'),
```

To:
```php
RegenerateJournalEntry::make('regenerateJournalEntry'),
```

- [ ] **Step 4: Remove Change Status bulk action (lines 300-316)**

Delete the entire `changeStatus` bulk action:
```php
\Filament\Actions\BulkAction::make('changeStatus')
    ->label('Change Status')
    ->icon('heroicon-o-pencil-square')
    ->color('primary')
    ->form([
        \Filament\Forms\Components\Select::make('status')
            ->label('Status')
            ->options([
                'draft' => 'Draft',
                'posted' => 'Posted',
            ])
            ->required(),
    ])
    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
        $records->each(fn ($record) => $record->update(['status' => $data['status']]));
    })
    ->deselectRecordsAfterCompletion(),
```

- [ ] **Step 5: Clean up unused imports**

Remove `Select` import if no longer used:
```php
use Filament\Forms\Components\Select;  // remove this line
```

- [ ] **Step 6: Commit**

```bash
git add app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php
git commit -m "feat: remove SalesOrder status display and invoice creation constraints"
```

---

### Task 2: Remove status field from SalesOrder form

**Files:**
- Modify: `app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php`

- [ ] **Step 1: Remove status Select field (lines 180-189)**

Delete the entire `Select::make('status')` block:
```php
Select::make('status')
    ->label('Status')
    ->options([
        'draft' => 'Draft',
    ])
    ->default('draft')
    ->required()
    ->native(false)
    ->disabled(fn ($record) => $record && ($record->deliveryDocuments()->exists() || $record->salesInvoices()->exists()))
    ->live(),
```

- [ ] **Step 2: Commit**

```bash
git add app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php
git commit -m "feat: remove status field from SalesOrder form"
```

---

### Task 3: Remove status constraint from SalesInvoice SO dropdown

**Files:**
- Modify: `app/Filament/Resources/SalesInvoices/Schemas/SalesInvoiceForm.php`

- [ ] **Step 1: Remove `where('status', 'posted')` (line 225)**

Delete this line:
```php
$query->where('status', 'posted');
```

Keep the `whereDoesntHave('salesInvoices', is_locked=true)` constraint (it prevents duplicate locked invoices, unrelated to status).

- [ ] **Step 2: Commit**

```bash
git add app/Filament/Resources/SalesInvoices/Schemas/SalesInvoiceForm.php
git commit -m "feat: allow all sales orders in invoice dropdown regardless of status"
```

---

### Task 4: Verify

- [ ] **Step 1: Verify SalesOrder table has no status column**

Check that the table renders without status.

- [ ] **Step 2: Verify "Create Invoice" button is visible for all orders with remaining amount**

- [ ] **Step 3: Verify SalesInvoice SO dropdown shows all orders (not just posted)**

- [ ] **Step 4: Run lint if available**
