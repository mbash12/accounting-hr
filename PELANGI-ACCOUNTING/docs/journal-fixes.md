# Journal Entry — Misconceptions, Unbalanced Entries & Fixes

## Background

The system auto-generates journal entries from source documents when they are posted through the PostingCenter. Eight document types trigger journal creation. The mapping between documents and journal entries is defined in `AccountMapping::DOCUMENT_MAPPING_TYPES` and the entry logic lives in `JournalService`.

---

## Misconception 1: All documents need journal entries

**Wrong assumption:** Every document type must create a journal entry when posted.

**Reality:** Sales Orders and Purchase Orders are **commitments** — no economic event has occurred. They should not create journal entries. The advance payment on an order is a separate event handled by the Cash Receipt / Cash Disbursement module.

**Before fix:** Both `sales_order` and `purchase_order` were in `DOCUMENT_MAPPING_TYPES` and the PostingCenter created empty or unbalanced journal entries for them.

**After fix:** `JournalService::createJournalEntryFromDocument` returns `null` immediately for these types. They are removed from `DOCUMENT_MAPPING_TYPES` and `ManageAccountMappings`.

---

## Misconception 2: Journal entries can be "completed later"

**Wrong assumption:** A journal can have only one side created now and the other side "handled separately."

**Reality:** Double-entry requires every journal to be **self-balancing**. You cannot create a journal with only a credit and expect the debit to come from another transaction.

**Before fix:**
```php
// Sales Order — only credit side:
Dr Cash/Bank (handled separately)   // NEVER CREATED
   Cr Advance Receivable   xxx      // ONLY THIS

// Purchase Order — only debit side:
Dr Advance Payable   xxx            // ONLY THIS
   Cr Cash/Bank (handled separately) // NEVER CREATED
```

**After fix:** These methods are deleted. Advance payments are journalized entirely within Cash Receipt/Disbursement.

---

## Misconception 3: Purchase Invoice should debit Purchases/Expenses

**Wrong assumption:** When a purchase invoice arrives, debit a purchases/expense account.

**Reality (perpetual inventory):** The cost was already captured when goods were received:
```
Goods Receipt:   Dr Inventory, Cr GRNI (liability)
Purchase Invoice: Dr GRNI (clears liability), Dr Tax, Cr A/P, Cr Discount
```

Debiting purchases/expenese again would **double-count** the cost — once as inventory (from GR) and once as expense (from PI).

**Before fix:**
```
Dr Purchases/Expenses   xxx
Dr Tax                  xxx
Dr Other Charges        xxx
   Cr A/P                    xxx
   Cr Discount               xxx
```

**After fix:**
```
Dr GRNI (clear accrual)  xxx
Dr Tax                   xxx
Dr Other Charges         xxx
   Cr A/P                     xxx
   Cr Discount                xxx
```

Falls back to debiting `purchases` if `grni` is not configured (backwards compatible for companies without goods receipt workflow).

---

## Misconception 4: Returns only reverse the base amount

**Wrong assumption:** A sales return just reverses the sale amount: `Dr Sales Return, Cr A/R`.

**Reality:** Returns must **proportionally reverse** tax, discount, and other charges from the original invoice. Otherwise:
- Tax liability account is overstated (credited on sale, never debited on return)
- Discount account is overstated (debited on sale, never credited on return)

**Before fix:**
```
Dr Sales Return   base_amount
   Cr A/R              base_amount
```

**After fix:**
```
Dr Sales Return      returnSubtotal (from items)
Dr Tax Payable       proportional_tax_amount
Dr Other Charges     proportional_other_charges
   Cr A/R                 return_total (net)
   Cr Discount            proportional_discount
```

Ratio: `returnSubtotal / originalInvoice.subtotal`. Falls back to simple reversal if no original invoice is linked.

---

## Critical: No balance validation existed

**Before fix:** Nothing checked that `total debits = total credits`. An unbalanced journal corrupted the ledger silently.

**After fix:** `createJournalItems` validates every journal entry before saving:
```php
if (abs($totalDebit - $totalCredit) > 0.005) {
    $journalEntry->delete();
    throw new RuntimeException("Journal entry #{$entry} does not balance: ...");
}
```

Empty entries (0 debit, 0 credit) are silently deleted.

---

## Summary of Changes

| File | Change |
|---|---|
| `app/Services/JournalService.php` | Removed order journal methods; added early return for orders; Purchase Invoice debits GRNI; Returns reverse tax/discount proportionally; balance validation added |
| `app/Models/AccountMapping.php` | Removed `sales_order`/`purchase_order` from DOCUMENT_TYPES and DOCUMENT_MAPPING_TYPES; added `tax`/`discount`/`other_charges` to return types; added `grni` to purchase_invoice |
| `app/Filament/Pages/ManageAccountMappings.php` | Removed `sales_order`/`purchase_order` from UI dropdown |

## Remaining Known Issues

1. **`is_posted` boolean + `status` enum** — duplicate source of truth for "is this posted"
2. **No reversal journal on unpost** — `Journalable` trait deletes entries on status change; should create reversing entries for audit trail
3. **`total_amount` vs `amount`** — ambiguous naming on JournalEntry (one is document total, one is debit sum)
