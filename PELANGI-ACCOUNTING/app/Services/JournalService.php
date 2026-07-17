<?php

namespace App\Services;

use App\Models\AccountMapping;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalService
{
    /**
     * Create or update a journal entry from a document based on account mappings
     *
     * @param string $documentType - The type of document (sales_order, sales_invoice, etc.)
     * @param \Illuminate\Database\Eloquent\Model $document - The document model instance
     * @param string $description - Description for journal entry
     * @return JournalEntry|null
     */
    public function createJournalEntryFromDocument(
        string $documentType,
        $document,
        string $description
    ): ?JournalEntry {
        $companyId = $document->company_id;

        if (!$companyId) {
            return null;
        }

        $documentDate = $document->date ?? now();
        app(PeriodClosingService::class)->assertOpen((int) $companyId, $documentDate);

        // Orders are commitments — no accounting impact, no journal entry.
        // Clean up any stale entries that may exist from before this policy was enforced.
        if (in_array($documentType, ['sales_order', 'purchase_order'])) {
            $this->deleteJournalEntriesForDocument($documentType, $document->id, $companyId);
            return null;
        }

        // Get mappings for this document type
        $mappings = AccountMapping::getMappingsForDocument($documentType, $companyId);

        if ($mappings->isEmpty()) {
            // No mappings configured - return null silently without creating journal
            return null;
        }

        DB::beginTransaction();

        try {
            // Check if journal entry already exists
            $existingEntry = JournalEntry::where('sub_module', $documentType)
                ->where('reference_type', get_class($document))
                ->where('reference_id', $document->id)
                ->where('company_id', $companyId)
                ->first();

            if ($existingEntry) {
                // Update existing journal entry
                $result = $this->updateJournalEntry($existingEntry, $document, $description, $mappings);
            } else {
                // Create new journal entry
                $result = $this->createNewJournalEntry($documentType, $document, $description, $mappings);
            }

            DB::commit();
            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a new journal entry
     */
    protected function createNewJournalEntry(
        string $documentType,
        $document,
        string $description,
        $mappings
    ): JournalEntry {
        // Create journal entry
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
            'created_by_user_id' => Auth::id() ?? 1,
            'updated_by_user_id' => Auth::id() ?? 1,
        ]);

        // Create journal entry items based on document type
        $this->createJournalItems($documentType, $document, $journalEntry, $mappings);

        return $journalEntry;
    }

    /**
     * Update an existing journal entry
     */
    protected function updateJournalEntry(
        JournalEntry $journalEntry,
        $document,
        string $description,
        $mappings
    ): JournalEntry {
        // Delete old journal entry items
        $journalEntry->items()->delete();

        // Update journal entry details
        $journalEntry->update([
            'date' => $document->date ?? now(),
            'reference_no' => $document->reference_no ?? $journalEntry->reference_no,
            'description' => $description,
            'total_amount' => $document->total_amount ?? 0,
            'updated_by_user_id' => Auth::id() ?? 1,
        ]);

        // Recreate journal entry items
        $this->createJournalItems($journalEntry->sub_module, $document, $journalEntry, $mappings);

        return $journalEntry;
    }

    /**
     * Create journal entry items based on document type
     */
    protected function createJournalItems(
        string $documentType,
        $document,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Orders are commitments — no accounting impact, no journal entry
        if (in_array($documentType, ['sales_order', 'purchase_order'])) {
            return;
        }

        switch ($documentType) {
            case 'delivery_document':
                $this->createSalesDeliveryJournalItems($document, $journalEntry, $mappings);
                break;

            case 'sales_invoice':
                $this->createSalesInvoiceJournalItems($document, $journalEntry, $mappings);
                break;

            case 'sales_return':
                $this->createSalesReturnJournalItems($document, $journalEntry, $mappings);
                break;

            case 'goods_receipt':
                $this->createGoodsReceiptJournalItems($document, $journalEntry, $mappings);
                break;

            case 'purchase_invoice':
                $this->createPurchaseInvoiceJournalItems($document, $journalEntry, $mappings);
                break;

            case 'purchase_return':
                $this->createPurchaseReturnJournalItems($document, $journalEntry, $mappings);
                break;
        }

        // Validate and update journal entry balance
        $totalDebit = $journalEntry->items()->sum('debit');
        $totalCredit = $journalEntry->items()->sum('credit');

        if ($totalDebit <= 0 && $totalCredit <= 0) {
            // No items created (e.g. order documents) — delete the empty entry
            $journalEntry->delete();
            return;
        }

        if (abs($totalDebit - $totalCredit) > 0.005) {
            $journalEntry->delete();
            throw new \RuntimeException(sprintf(
                'Journal entry #%s does not balance: debit %s, credit %s',
                $journalEntry->entry_number,
                $totalDebit,
                $totalCredit
            ));
        }

        $journalEntry->update(['amount' => $totalDebit]);
    }

    /**
     * Create journal items for Sales Delivery
     * Sales Delivery = COGS recognition when goods shipped
     * Dr COGS, Cr Inventory
     */
    protected function createSalesDeliveryJournalItems(
        $delivery,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        $cogsAmount = $this->calculateCOGS($delivery);
        
        if ($cogsAmount > 0) {
            // Debit: Cost of Goods Sold
            if ($mappings->has('cogs')) {
                $this->createJournalItem($journalEntry, $mappings->get('cogs'), 'debit', $cogsAmount);
            }

            // Credit: Inventory
            if ($mappings->has('inventory')) {
                $this->createJournalItem($journalEntry, $mappings->get('inventory'), 'credit', $cogsAmount);
            }
        }
    }

    /**
     * Create journal items for Sales Invoice
     * Sales Invoice = Revenue recognition
     * Dr A/R, Cr Sales (gross), Dr Discount, Cr Tax, Cr Other Charges
     */
    protected function createSalesInvoiceJournalItems(
        $invoice,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Debit: Accounts Receivable (total amount due from customer)
        if ($mappings->has('accounts_receivable') && ($invoice->total_amount ?? 0) > 0) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_receivable'), 'debit', $invoice->total_amount);
        }

        // Credit: Sales Revenue (gross sales before discount)
        $grossSales = $invoice->subtotal ?? 0;
        if ($mappings->has('sales') && $grossSales > 0) {
            $this->createJournalItem($journalEntry, $mappings->get('sales'), 'credit', $grossSales);
        }

        // Debit: Discount Given (contra revenue - reduces sales)
        if (($invoice->discount ?? 0) > 0 && $mappings->has('discount')) {
            $this->createJournalItem($journalEntry, $mappings->get('discount'), 'debit', $invoice->discount);
        }

        // Credit: Tax Payable (output VAT)
        if (($invoice->tax_amount ?? 0) > 0 && $mappings->has('tax')) {
            $this->createJournalItem($journalEntry, $mappings->get('tax'), 'credit', $invoice->tax_amount);
        }

        // Credit: Other / Additional Charges (per-row COA when available)
        $this->postOtherCharges($invoice, $journalEntry, $mappings, 'credit');
    }

    /**
     * Create journal items for Sales Return
     * Sales Return = Reverse original sale proportionally, including tax and discount
     * Dr Sales Return, Dr Tax, Dr Other Charges, Cr A/R, Cr Discount
     */
    protected function createSalesReturnJournalItems(
        $return,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        $returnSubtotal = $this->calculateReturnTotal($return);
        if ($returnSubtotal <= 0) {
            return;
        }

        // Try to get proportional tax/discount from the original invoice
        $taxAmount = 0;
        $discountAmount = 0;
        $otherChargesAmount = 0;
        $ratio = 0;

        $originalInvoice = $return->salesInvoice ?? null;
        if ($originalInvoice && ($originalInvoice->subtotal ?? 0) > 0) {
            $ratio = $returnSubtotal / $originalInvoice->subtotal;
            $taxAmount = round(($originalInvoice->tax_amount ?? 0) * $ratio, 2);
            $discountAmount = round(($originalInvoice->discount ?? 0) * $ratio, 2);
        }

        // Debit: Sales Returns (contra-revenue — reverses original sales credit)
        if ($mappings->has('sales_return')) {
            $this->createJournalItem($journalEntry, $mappings->get('sales_return'), 'debit', $returnSubtotal);
        }

        // Debit: Tax Payable (reverses original output VAT credit)
        if ($taxAmount > 0 && $mappings->has('tax')) {
            $this->createJournalItem($journalEntry, $mappings->get('tax'), 'debit', $taxAmount);
        }

        // Debit: Other / Additional Charges (reverse original charges)
        if ($originalInvoice) {
            $otherChargesAmount = $this->postOtherCharges($originalInvoice, $journalEntry, $mappings, 'debit', $ratio);
        }

        // Credit: Discount (reverses original discount debit — contra-revenue reversal)
        if ($discountAmount > 0 && $mappings->has('discount')) {
            $this->createJournalItem($journalEntry, $mappings->get('discount'), 'credit', $discountAmount);
        }

        // Credit: Accounts Receivable (net amount customer no longer owes)
        $returnTotal = $returnSubtotal - $discountAmount + $taxAmount + $otherChargesAmount;
        if ($returnTotal > 0 && $mappings->has('accounts_receivable')) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_receivable'), 'credit', $returnTotal);
        }
    }

    /**
     * Create journal items for Goods Receipt
     * Goods Receipt = Inventory received, pending invoice
     * Dr Inventory, Cr GRNI (Goods Received Not Invoiced)
     */
    protected function createGoodsReceiptJournalItems(
        $receipt,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        $inventoryAmount = $this->calculateInventoryValue($receipt);
        
        if ($inventoryAmount > 0) {
            // Debit: Inventory (receive goods into stock)
            if ($mappings->has('inventory')) {
                $this->createJournalItem($journalEntry, $mappings->get('inventory'), 'debit', $inventoryAmount);
            }

            // Credit: GRNI (Goods Received Not Invoiced - liability until invoice received)
            if ($mappings->has('grni')) {
                $this->createJournalItem($journalEntry, $mappings->get('grni'), 'credit', $inventoryAmount);
            }
        }
    }

    /**
     * Create journal items for Purchase Invoice
     * Purchase Invoice = Clear GRNI accrual and record tax/discount liability
     * Dr GRNI (clear accrual), Dr Tax, Dr Other Charges, Cr A/P, Cr Discount
     * Falls back to Dr Purchases if GRNI mapping is not configured.
     */
    protected function createPurchaseInvoiceJournalItems(
        $invoice,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Debit: GRNI (clear goods received not invoiced accrual)
        // Falls back to Purchases/Expenses if GRNI not mapped (direct expense, no prior receipt)
        $grossAmount = $invoice->subtotal ?? 0;
        if ($grossAmount > 0) {
            if ($mappings->has('grni')) {
                $this->createJournalItem($journalEntry, $mappings->get('grni'), 'debit', $grossAmount);
            } elseif ($mappings->has('purchases')) {
                $this->createJournalItem($journalEntry, $mappings->get('purchases'), 'debit', $grossAmount);
            }
        }

        // Credit: Discount Received (reduce purchase cost)
        if (($invoice->discount ?? 0) > 0 && $mappings->has('discount')) {
            $this->createJournalItem($journalEntry, $mappings->get('discount'), 'credit', $invoice->discount);
        }

        // Debit: Tax (input VAT - recoverable)
        if (($invoice->tax_amount ?? 0) > 0 && $mappings->has('tax')) {
            $this->createJournalItem($journalEntry, $mappings->get('tax'), 'debit', $invoice->tax_amount);
        }

        // Debit: Other / Additional Charges (per-row COA when available)
        $this->postOtherCharges($invoice, $journalEntry, $mappings, 'debit');

        // Credit: Accounts Payable (total amount owed to supplier)
        if ($mappings->has('accounts_payable') && ($invoice->total_amount ?? 0) > 0) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_payable'), 'credit', $invoice->total_amount);
        }
    }

    /**
     * Create journal items for Purchase Return
     * Purchase Return = Reverse original purchase proportionally, including tax and discount
     * Dr A/P, Dr Discount, Cr Purchase Return, Cr Tax, Cr Other Charges
     */
    protected function createPurchaseReturnJournalItems(
        $return,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        $returnSubtotal = $this->calculateReturnTotal($return);
        if ($returnSubtotal <= 0) {
            return;
        }

        // Try to get proportional tax/discount from the original invoice
        $taxAmount = 0;
        $discountAmount = 0;
        $otherChargesAmount = 0;
        $ratio = 0;

        $originalInvoice = $return->purchaseInvoice ?? null;
        if ($originalInvoice && ($originalInvoice->subtotal ?? 0) > 0) {
            $ratio = $returnSubtotal / $originalInvoice->subtotal;
            $taxAmount = round(($originalInvoice->tax_amount ?? 0) * $ratio, 2);
            $discountAmount = round(($originalInvoice->discount ?? 0) * $ratio, 2);
            if (method_exists($originalInvoice, 'otherCharges')) {
                $originalInvoice->loadMissing('otherCharges');
                $otherChargesAmount = round(
                    (float) $originalInvoice->otherCharges->sum('amount') * $ratio,
                    2
                );
            } else {
                $otherChargesAmount = round(($originalInvoice->other_charges ?? 0) * $ratio, 2);
            }
        }

        // Debit: Accounts Payable (reduce amount owed to supplier)
        $returnTotal = $returnSubtotal - $discountAmount + $taxAmount + $otherChargesAmount;
        if ($returnTotal > 0 && $mappings->has('accounts_payable')) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_payable'), 'debit', $returnTotal);
        }

        // Debit: Discount (reverses original purchase discount credit)
        if ($discountAmount > 0 && $mappings->has('discount')) {
            $this->createJournalItem($journalEntry, $mappings->get('discount'), 'debit', $discountAmount);
        }

        // Credit: Purchase Returns (contra-expense — reverses original purchases debit)
        if ($mappings->has('purchase_return')) {
            $this->createJournalItem($journalEntry, $mappings->get('purchase_return'), 'credit', $returnSubtotal);
        }

        // Credit: Tax (reverses original input VAT debit)
        if ($taxAmount > 0 && $mappings->has('tax')) {
            $this->createJournalItem($journalEntry, $mappings->get('tax'), 'credit', $taxAmount);
        }

        // Credit: Other / Additional Charges (reverse original charges)
        if ($originalInvoice) {
            $this->postOtherCharges($originalInvoice, $journalEntry, $mappings, 'credit', $ratio);
        }
    }

    /**
     * Create a journal entry item
     */
    protected function createJournalItem(
        JournalEntry $journalEntry,
        $account,
        string $type,
        float $amount,
        ?string $notes = null
    ): void {
        if (!$account || $amount <= 0) {
            return;
        }

        JournalEntryItem::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $account->id,
            'debit' => $type === 'debit' ? $amount : 0,
            'credit' => $type === 'credit' ? $amount : 0,
            'notes' => $notes,
        ]);
    }

    /**
     * Post other/additional charges. Prefer per-row COA when charge rows exist.
     * Returns the total amount actually posted (after optional ratio).
     */
    protected function postOtherCharges(
        $document,
        JournalEntry $journalEntry,
        $mappings,
        string $type,
        ?float $ratio = null
    ): float {
        $posted = 0.0;

        if ($document && method_exists($document, 'otherCharges')) {
            $document->loadMissing('otherCharges.account');
            $rows = $document->otherCharges ?? collect();

            if ($rows->isNotEmpty()) {
                foreach ($rows as $row) {
                    $amount = (float) ($row->amount ?? 0);
                    if ($ratio !== null) {
                        $amount = round($amount * $ratio, 2);
                    }

                    if ($amount <= 0) {
                        continue;
                    }

                    $account = $row->account;
                    if (!$account && $mappings->has('other_charges')) {
                        $account = $mappings->get('other_charges');
                    }

                    if (!$account) {
                        $label = $row->name ?? 'Other Charges';
                        throw new \RuntimeException(
                            "Cannot post journal: other charge \"{$label}\" has no COA and no other_charges account mapping."
                        );
                    }

                    $this->createJournalItem(
                        $journalEntry,
                        $account,
                        $type,
                        $amount,
                        $row->name ?? null,
                    );
                    $posted += $amount;
                }

                return round($posted, 2);
            }
        }

        $amount = (float) ($document->other_charges ?? 0);
        if ($ratio !== null) {
            $amount = round($amount * $ratio, 2);
        }

        if ($amount > 0) {
            if (!$mappings->has('other_charges')) {
                throw new \RuntimeException(
                    'Cannot post journal: other charges exist but other_charges account mapping is missing.'
                );
            }
            $this->createJournalItem($journalEntry, $mappings->get('other_charges'), $type, $amount);
            $posted = $amount;
        }

        return round($posted, 2);
    }

    /**
     * Calculate COGS for a delivery document (from product cost)
     */
    protected function calculateCOGS($document): float
    {
        // Load items with product if not already loaded
        if (!$document->relationLoaded('items')) {
            $document->load('items.product');
        }
        
        $items = $document->items ?? collect();

        return $items->sum(function ($item) {
            $quantity = $item->quantity ?? $item->total_quantity ?? $item->qty ?? 0;
            // Try item cost first, then product cost_price
            $unitCost = $item->unit_cost ?? $item->cost ?? 0;
            if ($unitCost <= 0) {
                $product = $item->product ?? \App\Models\Product::find($item->product_id);
                $unitCost = $product->cost_price ?? 0;
            }
            return (float) $quantity * (float) $unitCost;
        });
    }

    /**
     * Calculate inventory value for goods receipt (from product cost or unit_price)
     */
    protected function calculateInventoryValue($document): float
    {
        // Load items with product if not already loaded
        if (!$document->relationLoaded('items')) {
            $document->load('items.product');
        }
        
        $items = $document->items ?? collect();

        return $items->sum(function ($item) {
            $quantity = $item->quantity ?? $item->total_quantity ?? $item->qty ?? 0;
            // Try item unit_price first (for goods receipt), then product cost_price
            $unitCost = $item->unit_price ?? $item->unit_cost ?? $item->cost ?? 0;
            if ($unitCost <= 0) {
                $product = $item->product ?? \App\Models\Product::find($item->product_id);
                $unitCost = $product->cost_price ?? 0;
            }
            return (float) $quantity * (float) $unitCost;
        });
    }

    /**
     * Calculate return total from items (quantity * unit_price from linked document or product)
     */
    protected function calculateReturnTotal($document): float
    {
        // Load items with product if not already loaded
        if (!$document->relationLoaded('items')) {
            $document->load('items.product');
        }
        
        $items = $document->items ?? collect();

        return $items->sum(function ($item) {
            $quantity = $item->quantity ?? $item->total_quantity ?? $item->qty ?? 0;
            
            // Try item unit_price first
            $unitPrice = $item->unit_price ?? $item->price ?? 0;
            
            // If no price, try to get from linked delivery/receipt item
            if ($unitPrice <= 0 && $item->delivery_document_item_id) {
                $deliveryItem = \App\Models\DeliveryDocumentItem::with('salesOrderItem')->find($item->delivery_document_item_id);
                $unitPrice = $deliveryItem?->salesOrderItem?->unit_price ?? 0;
            }
            if ($unitPrice <= 0 && $item->goods_receipt_item_id) {
                $receiptItem = \App\Models\GoodsReceiptItem::find($item->goods_receipt_item_id);
                $unitPrice = $receiptItem?->unit_cost ?? 0;
            }
            
            // Fallback to product selling_price
            if ($unitPrice <= 0) {
                $product = $item->product ?? \App\Models\Product::find($item->product_id);
                $unitPrice = $product->selling_price ?? $product->cost_price ?? 0;
            }
            
            return (float) $quantity * (float) $unitPrice;
        });
    }

    /**
     * Generate a unique journal entry number
     */
    protected function generateEntryNumber(): string
    {
        $prefix = 'JE';
        $date = now()->format('Ymd');

        $lastEntry = JournalEntry::where('entry_number', 'like', $prefix . $date . '%')
            ->orderBy('entry_number', 'desc')
            ->first();

        if ($lastEntry) {
            $lastNumber = (int) substr($lastEntry->entry_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Delete journal entries for a document
     */
    public function deleteJournalEntriesForDocument(
        string $documentType,
        int $documentId,
        int $companyId
    ): void {
        $journalEntries = JournalEntry::where('sub_module', $documentType)
            ->where('reference_id', $documentId)
            ->where('company_id', $companyId)
            ->get();

        foreach ($journalEntries as $entry) {
            // Delete journal entry items first
            $entry->items()->delete();
            // Then delete journal entry
            $entry->delete();
        }
    }
}
