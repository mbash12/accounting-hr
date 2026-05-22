<?php

namespace App\Imports;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\PurchaseOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PurchaseInvoiceWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by invoice number to process invoices and their items together
        $invoicesData = [];

        foreach ($rows as $row) {
            $invoiceNumber = (string) $row['invoice_no'];

            if (!isset($invoicesData[$invoiceNumber])) {
                // Create the invoice data
                $supplierId = null;
                $supplier = null;

                if (!empty($row['supplier_code'])) {
                    $supplier = Contact::where('contact_code', (string) $row['supplier_code'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                }

                if (!$supplier && !empty($row['supplier_name'])) {
                    $supplier = Contact::where('name', (string) $row['supplier_name'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                }

                if (!$supplier) {
                    // Create supplier if not found
                    if (empty($row['supplier_name']) && empty($row['supplier_code'])) {
                         throw new \Exception("Either Supplier Code or Supplier Name is required for invoice {$invoiceNumber}");
                    }

                    $supplierData = [
                        'name' => (string) $row['supplier_name'] ?: (string) $row['supplier_code'],
                        'company_id' => $companyId,
                        'is_supplier' => true,
                        'created_by_user_id' => Auth::id(),
                    ];

                    if (!empty($row['supplier_code'])) {
                        $supplierData['contact_code'] = (string) $row['supplier_code'];
                    }

                    $supplier = Contact::create($supplierData);
                }
                $supplierId = $supplier->id;

                $purchaseOrderId = null;
                if (!empty($row['purchase_order_no'])) {
                    $purchaseOrder = PurchaseOrder::where('purchase_order_no', (string) $row['purchase_order_no'])
                        ->where('company_id', $companyId)
                        ->where('supplier_id', $supplierId)
                        ->first();
                    if ($purchaseOrder) {
                        $purchaseOrderId = $purchaseOrder->id;
                    }
                }

                $invoicesData[$invoiceNumber] = [
                    'invoice_data' => [
                        'invoice_number' => $invoiceNumber,
                        'date' => isset($row['date']) ? $this->parseDate($row['date']) : now()->format('Y-m-d'),
                        'due_date' => isset($row['due_date']) ? $this->parseDate($row['due_date']) : null,
                        'reference_no' => isset($row['reference_no']) ? (string) $row['reference_no'] : null,
                        'description' => isset($row['description']) ? (string) $row['description'] : null,
                        'other_charges' => isset($row['other_charges']) ? (float) $row['other_charges'] : 0,
                        'discount' => isset($row['discount']) ? (float) $row['discount'] : 0,
                        'discount_percentage' => isset($row['discount_pct']) ? (float) $row['discount_pct'] : 0,
                        'subtotal' => isset($row['subtotal']) ? (float) $row['subtotal'] : 0,
                        'tax_amount' => isset($row['tax']) ? (float) $row['tax'] : 0,
                        'total' => isset($row['total']) ? (float) $row['total'] : 0,
                        'paid_amount' => isset($row['paid_amount']) ? (float) $row['paid_amount'] : 0,
                        'outstanding_amount' => isset($row['outstanding_amount']) ? (float) $row['outstanding_amount'] : 0,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'purchase_order_id' => $purchaseOrderId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this invoice
            $productId = null;
            $product = null;

            if (!empty($row['product_code'])) {
                $product = Product::where('code', (string) $row['product_code'])
                    ->where('company_id', $companyId)
                    ->first();
            }

            if (!$product && !empty($row['product_name'])) {
                $product = Product::where('name', (string) $row['product_name'])
                    ->where('company_id', $companyId)
                    ->first();
            }

            if (!$product) {
                // Create product if it doesn't exist
                $productData = [
                    'name' => (string) $row['product_name'] ?: ((string) $row['product_code'] ?: 'New Product'),
                    'company_id' => $companyId,
                    'cost_price' => isset($row['unit_price']) ? (float) $row['unit_price'] : 0,
                    'created_by_user_id' => Auth::id(),
                ];

                if (!empty($row['product_code'])) {
                    $productData['code'] = (string) $row['product_code'];
                }

                $product = Product::create($productData);
            }
            $productId = $product->id;

            $unitId = null;
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company for invoice {$invoiceNumber}");
                }
                $unitId = $unit->id;
            }

            $taxId = null;
            if (!empty($row['tax_code'])) {
                $tax = Tax::where('code', (string) $row['tax_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$tax) {
                    throw new \Exception("Tax with code '{$row['tax_code']}' not found in current company for invoice {$invoiceNumber}");
                }
                $taxId = $tax->id;
            }

            $invoicesData[$invoiceNumber]['items'][] = [
                'description' => isset($row['item_description']) ? (string) $row['item_description'] : null,
                'quantity' => isset($row['quantity']) ? (float) $row['quantity'] : 0,
                'unit_price' => isset($row['unit_price']) ? (float) $row['unit_price'] : 0,
                'total' => isset($row['item_total']) ? (float) $row['item_total'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'tax_id' => $taxId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each invoice and its items
        foreach ($invoicesData as $invoiceNumber => $invoiceData) {
            $purchaseInvoice = PurchaseInvoice::where('invoice_number', $invoiceNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($purchaseInvoice) {
                // Update existing invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseInvoice::getEventDispatcher();
                PurchaseInvoice::unsetEventDispatcher();

                try {
                    $purchaseInvoice->update($invoiceData['invoice_data']);
                    $purchaseInvoice->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    PurchaseInvoice::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseInvoice::getEventDispatcher();
                PurchaseInvoice::unsetEventDispatcher();

                try {
                    $purchaseInvoice = new PurchaseInvoice();
                    $purchaseInvoice->forceFill($invoiceData['invoice_data']);
                    $purchaseInvoice->save();
                } finally {
                    // Re-enable model events
                    PurchaseInvoice::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the invoice
            foreach ($invoiceData['items'] as $itemData) {
                $itemData['purchase_invoice_id'] = $purchaseInvoice->id;
                PurchaseInvoiceItem::create($itemData);
            }

            // Calculate totals from items
            $items = $purchaseInvoice->items;
            $subtotal = $items->sum('total');
            $discountPercentage = $purchaseInvoice->discount_percentage ?? 0;
            $discount = $purchaseInvoice->discount ?? 0;
            $otherCharges = $purchaseInvoice->other_charges ?? 0;

            if ($discountPercentage > 0 && $subtotal > 0) {
                $discount = $subtotal * ($discountPercentage / 100);
            }

            // Calculate tax amount based on items with tax
            $taxAmount = 0;
            foreach ($items as $item) {
                if ($item->tax_id) {
                    $tax = Tax::find($item->tax_id);
                    if ($tax) {
                        $lineTotal = $item->total;
                        $lineDiscount = $lineTotal * ($discountPercentage / 100);
                        $taxBase = $lineTotal - $lineDiscount;
                        $taxAmount += $taxBase * ($tax->tax_percentage / 100);
                    }
                }
            }

            $totalAmount = $subtotal - $discount + $otherCharges + $taxAmount;
            $paidAmount = $purchaseInvoice->paid_amount ?? 0;
            $outstandingAmount = $totalAmount - $paidAmount;

            // Temporarily disable model events again for the totals update to avoid journal creation for draft status
            $dispatcher = PurchaseInvoice::getEventDispatcher();
            PurchaseInvoice::unsetEventDispatcher();

            try {
                $purchaseInvoice->update([
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'total' => $totalAmount,
                    'outstanding_amount' => $outstandingAmount,
                ]);
            } finally {
                // Re-enable model events
                PurchaseInvoice::setEventDispatcher($dispatcher);
            }

            // Refresh invoice tracking if there's a purchase order
            if ($purchaseInvoice->purchase_order_id) {
                $purchaseInvoice->purchaseOrder?->refreshInvoiceTracking();
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'invoice_no' => isset($data['invoice_no']) ? (string) $data['invoice_no'] : null,
            'date' => isset($data['date']) ? (string) $data['date'] : null,
            'due_date' => isset($data['due_date']) ? (string) $data['due_date'] : null,
            'reference_no' => isset($data['reference_no']) ? (string) $data['reference_no'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'other_charges' => isset($data['other_charges']) ? (string) $data['other_charges'] : null,
            'discount' => isset($data['discount']) ? (string) $data['discount'] : null,
            'discount_pct' => isset($data['discount_pct']) ? (string) $data['discount_pct'] : null,
            'subtotal' => isset($data['subtotal']) ? (string) $data['subtotal'] : null,
            'tax' => isset($data['tax']) ? (string) $data['tax'] : null,
            'total' => isset($data['total']) ? (string) $data['total'] : null,
            'paid_amount' => isset($data['paid_amount']) ? (string) $data['paid_amount'] : null,
            'outstanding_amount' => isset($data['outstanding_amount']) ? (string) $data['outstanding_amount'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'supplier_code' => isset($data['supplier_code']) ? (string) $data['supplier_code'] : null,
            'supplier_name' => isset($data['supplier_name']) ? (string) $data['supplier_name'] : null,
            'purchase_order_no' => isset($data['purchase_order_no']) ? (string) $data['purchase_order_no'] : null,
            'product_code' => isset($data['product_code']) ? (string) $data['product_code'] : null,
            'product_name' => isset($data['product_name']) ? (string) $data['product_name'] : null,
            'item_description' => isset($data['item_description']) ? (string) $data['item_description'] : null,
            'quantity' => isset($data['quantity']) ? (string) $data['quantity'] : null,
            'unit_price' => isset($data['unit_price']) ? (string) $data['unit_price'] : null,
            'item_total' => isset($data['item_total']) ? (string) $data['item_total'] : null,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
            'tax_code' => isset($data['tax_code']) ? (string) $data['tax_code'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'invoice_no' => 'required|string|max:50',
            'date' => 'required',
            'due_date' => 'nullable',
            'reference_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'other_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_pct' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'outstanding_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,posted',
            'supplier_code' => 'nullable|string|max:50',
            'supplier_name' => 'nullable|string|max:255',
            'purchase_order_no' => 'nullable|string|max:100',
            'product_code' => 'required_without:product_name|nullable|string|max:50',
            'product_name' => 'required_without:product_code|nullable|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'item_total' => 'nullable|numeric|min:0',
            'unit_code' => 'nullable|string|max:20',
            'tax_code' => 'nullable|string|max:50',
        ];
    }

    /**
     * Parse date from various formats that might come from Excel
     */
    private function parseDate($dateValue)
    {
        // If it's already in YYYY-MM-DD format, return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($dateValue))) {
            return trim($dateValue);
        }

        // Handle formats like 1/1/2024, 01/01/2024, etc.
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($dateValue), $matches)) {
            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return $year . '-' . $month . '-' . $day;
        }

        // Try to parse with Carbon for other formats
        try {
            $carbonDate = \Carbon\Carbon::parse($dateValue);
            return $carbonDate->format('Y-m-d');
        } catch (\Exception $e) {
            // If parsing fails, return current date
            return now()->format('Y-m-d');
        }
    }

    public function customValidationMessages()
    {
        return [
            'invoice_no.required' => 'Invoice Number is required.',
            'invoice_no.max' => 'Invoice Number cannot exceed 50 characters.',
            'date.required' => 'Date is required.',
            'due_date.date' => 'Due Date must be a valid date.',
            'reference_no.max' => 'Reference Number cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'other_charges.min' => 'Other Charges cannot be less than 0.',
            'other_charges.numeric' => 'Other Charges must be a number.',
            'discount.min' => 'Discount cannot be less than 0.',
            'discount.numeric' => 'Discount must be a number.',
            'discount_pct.min' => 'Discount Percentage cannot be less than 0.',
            'discount_pct.max' => 'Discount Percentage cannot exceed 100.',
            'discount_pct.numeric' => 'Discount Percentage must be a number.',
            'subtotal.min' => 'Subtotal cannot be less than 0.',
            'subtotal.numeric' => 'Subtotal must be a number.',
            'tax.min' => 'Tax cannot be less than 0.',
            'tax.numeric' => 'Tax must be a number.',
            'total.min' => 'Total cannot be less than 0.',
            'total.numeric' => 'Total must be a number.',
            'paid_amount.min' => 'Paid Amount cannot be less than 0.',
            'paid_amount.numeric' => 'Paid Amount must be a number.',
            'outstanding_amount.min' => 'Outstanding Amount cannot be less than 0.',
            'outstanding_amount.numeric' => 'Outstanding Amount must be a number.',
            'supplier_code.max' => 'Supplier Code cannot exceed 50 characters.',
            'supplier_name.max' => 'Supplier Name cannot exceed 255 characters.',
            'purchase_order_no.max' => 'Purchase Order Number cannot exceed 100 characters.',
            'product_code.max' => 'Product Code cannot exceed 50 characters.',
            'product_name.max' => 'Product Name cannot exceed 255 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity cannot be less than 0.',
            'quantity.numeric' => 'Quantity must be a number.',
            'unit_price.required' => 'Unit Price is required.',
            'unit_price.min' => 'Unit Price cannot be less than 0.',
            'unit_price.numeric' => 'Unit Price must be a number.',
            'item_total.min' => 'Item Total cannot be less than 0.',
            'item_total.numeric' => 'Item Total must be a number.',
            'unit_code.max' => 'Unit Code cannot exceed 20 characters.',
            'tax_code.max' => 'Tax Code cannot exceed 50 characters.',
        ];
    }
}
