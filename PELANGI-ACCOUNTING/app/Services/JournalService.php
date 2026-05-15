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
            'status' => 'posted',
            'is_posted' => true,
            'sub_module' => $documentType,
            'reference_type' => get_class($document),
            'reference_id' => $document->id,
            'posted_by_user_id' => Auth::id(),
            'posted_at' => now(),
            'company_id' => $document->company_id,
            'created_by_user_id' => Auth::id(),
            'updated_by_user_id' => Auth::id(),
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
            'updated_by_user_id' => Auth::id(),
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
        switch ($documentType) {
            case 'sales_order':
                $this->createSalesOrderJournalItems($document, $journalEntry, $mappings);
                break;

            case 'delivery_document':
                $this->createSalesDeliveryJournalItems($document, $journalEntry, $mappings);
                break;

            case 'sales_invoice':
                $this->createSalesInvoiceJournalItems($document, $journalEntry, $mappings);
                break;

            case 'sales_return':
                $this->createSalesReturnJournalItems($document, $journalEntry, $mappings);
                break;

            case 'purchase_order':
                $this->createPurchaseOrderJournalItems($document, $journalEntry, $mappings);
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

        // Update journal entry amount with total debits
        $totalDebit = $journalEntry->items()->sum('debit');
        $journalEntry->update(['amount' => $totalDebit]);
    }

    /**
     * Create journal items for Sales Order
     * Sales Order = No journal entry (commitment only)
     * Only create if advance payment is received
     */
    protected function createSalesOrderJournalItems(
        $salesOrder,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Sales Order has no accounting impact - it's just a commitment
        // Journal entry only created if advance payment is received
        if (isset($salesOrder->advance_amount) && $salesOrder->advance_amount > 0 && $mappings->has('advance_receivable')) {
            // Debit: Cash/Bank (handled separately in payment)
            // Credit: Advance Receivable (customer deposit liability)
            $this->createJournalItem($journalEntry, $mappings->get('advance_receivable'), 'credit', $salesOrder->advance_amount);
        }
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

        // Credit: Other Charges (additional revenue)
        if (($invoice->other_charges ?? 0) > 0 && $mappings->has('other_charges')) {
            $this->createJournalItem($journalEntry, $mappings->get('other_charges'), 'credit', $invoice->other_charges);
        }
    }

    /**
     * Create journal items for Sales Return
     * Sales Return = Reverse revenue (calculated from items)
     * Dr Sales Return, Cr A/R
     */
    protected function createSalesReturnJournalItems(
        $return,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Calculate total from items
        $totalAmount = $this->calculateReturnTotal($return);
        
        if ($totalAmount <= 0) {
            return;
        }

        // Credit: Accounts Receivable (reduce amount due from customer)
        if ($mappings->has('accounts_receivable')) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_receivable'), 'credit', $totalAmount);
        }

        // Debit: Sales Returns (contra revenue)
        if ($mappings->has('sales_return')) {
            $this->createJournalItem($journalEntry, $mappings->get('sales_return'), 'debit', $totalAmount);
        }
    }

    /**
     * Create journal items for Purchase Order
     * Purchase Order = No journal entry (commitment only)
     * Only create if advance payment is made
     */
    protected function createPurchaseOrderJournalItems(
        $order,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Purchase Order has no accounting impact - it's just a commitment
        // Journal entry only created if advance payment is made
        if (isset($order->advance_amount) && $order->advance_amount > 0 && $mappings->has('advance_payable')) {
            // Debit: Advance Payable (prepayment to supplier)
            $this->createJournalItem($journalEntry, $mappings->get('advance_payable'), 'debit', $order->advance_amount);
            // Credit: Cash/Bank (handled separately in payment)
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
     * Purchase Invoice = Record liability and expense/inventory
     * Dr Expense/Inventory, Dr Tax, Cr A/P, Cr Discount
     */
    protected function createPurchaseInvoiceJournalItems(
        $invoice,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Debit: Expense or Inventory (subtotal before discount)
        $grossAmount = $invoice->subtotal ?? 0;
        if ($grossAmount > 0 && $mappings->has('purchases')) {
            $this->createJournalItem($journalEntry, $mappings->get('purchases'), 'debit', $grossAmount);
        }

        // Credit: Discount Received (reduce purchase cost)
        if (($invoice->discount ?? 0) > 0 && $mappings->has('discount')) {
            $this->createJournalItem($journalEntry, $mappings->get('discount'), 'credit', $invoice->discount);
        }

        // Debit: Tax (input VAT - recoverable)
        if (($invoice->tax_amount ?? 0) > 0 && $mappings->has('tax')) {
            $this->createJournalItem($journalEntry, $mappings->get('tax'), 'debit', $invoice->tax_amount);
        }

        // Debit: Other Charges (freight, handling, etc.)
        if (($invoice->other_charges ?? 0) > 0 && $mappings->has('other_charges')) {
            $this->createJournalItem($journalEntry, $mappings->get('other_charges'), 'debit', $invoice->other_charges);
        }

        // Credit: Accounts Payable (total amount owed to supplier)
        if ($mappings->has('accounts_payable') && ($invoice->total_amount ?? 0) > 0) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_payable'), 'credit', $invoice->total_amount);
        }
    }

    /**
     * Create journal items for Purchase Return
     * Purchase Return = Reverse purchase (calculated from items)
     * Dr A/P, Cr Purchase Return
     */
    protected function createPurchaseReturnJournalItems(
        $return,
        JournalEntry $journalEntry,
        $mappings
    ): void {
        // Calculate total from items
        $totalAmount = $this->calculateReturnTotal($return);
        
        if ($totalAmount <= 0) {
            return;
        }

        // Debit: Accounts Payable (reduce amount owed to supplier)
        if ($mappings->has('accounts_payable')) {
            $this->createJournalItem($journalEntry, $mappings->get('accounts_payable'), 'debit', $totalAmount);
        }

        // Credit: Purchase Returns
        if ($mappings->has('purchase_return')) {
            $this->createJournalItem($journalEntry, $mappings->get('purchase_return'), 'credit', $totalAmount);
        }
    }

    /**
     * Create a journal entry item
     */
    protected function createJournalItem(
        JournalEntry $journalEntry,
        $account,
        string $type,
        float $amount
    ): void {
        if (!$account || $amount <= 0) {
            return;
        }

        JournalEntryItem::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $account->id,
            'debit' => $type === 'debit' ? $amount : 0,
            'credit' => $type === 'credit' ? $amount : 0,
            'notes' => null,
        ]);
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
