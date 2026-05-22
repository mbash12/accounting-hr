<?php

namespace App\Imports;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GoodsReceiptWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by receipt number to process receipts and their items together
        $receiptsData = [];

        foreach ($rows as $row) {
            $receiptNumber = (string) $row['receipt_no'];

            if (!isset($receiptsData[$receiptNumber])) {
                // Create the receipt data
                $supplierId = null;
                if (!empty($row['supplier_code'])) {
                    $supplier = Contact::where('contact_code', (string) $row['supplier_code'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                    if (!$supplier) {
                        throw new \Exception("Supplier with code '{$row['supplier_code']}' not found in current company");
                    }
                    $supplierId = $supplier->id;
                } elseif (!empty($row['supplier_name'])) {
                    $supplier = Contact::where('name', (string) $row['supplier_name'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                    if (!$supplier) {
                        throw new \Exception("Supplier with name '{$row['supplier_name']}' not found in current company");
                    }
                    $supplierId = $supplier->id;
                } else {
                    throw new \Exception("Either Supplier Code or Supplier Name is required for receipt {$receiptNumber}");
                }

                $purchaseOrderId = null;
                if (!empty($row['purchase_order_no'])) {
                    $purchaseOrder = PurchaseOrder::where('purchase_order_no', (string) $row['purchase_order_no'])
                        ->where('company_id', $companyId)
                        ->where('supplier_id', $supplierId)
                        ->first();
                    if (!$purchaseOrder) {
                        throw new \Exception("Purchase Order with number '{$row['purchase_order_no']}' not found for supplier in current company for receipt {$receiptNumber}");
                    }
                    $purchaseOrderId = $purchaseOrder->id;
                }

                $receiptsData[$receiptNumber] = [
                    'receipt_data' => [
                        'receipt_number' => $receiptNumber,
                        'date' => isset($row['date']) ? $this->parseDate($row['date']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['reference_no']) ? (string) $row['reference_no'] : null,
                        'description' => isset($row['description']) ? (string) $row['description'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'purchase_order_id' => $purchaseOrderId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this receipt
            $productId = null;
            if (!empty($row['product_code'])) {
                $product = Product::where('code', (string) $row['product_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['product_code']}' not found in current company for receipt {$receiptNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['product_name'])) {
                $product = Product::where('name', (string) $row['product_name'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['product_name']}' not found in current company for receipt {$receiptNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Product Code or Product Name is required for receipt {$receiptNumber}");
            }

            $unitId = null;
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company for receipt {$receiptNumber}");
                }
                $unitId = $unit->id;
            }

            $receiptsData[$receiptNumber]['items'][] = [
                'description' => isset($row['item_description']) ? (string) $row['item_description'] : null,
                'quantity' => isset($row['quantity']) ? (float) $row['quantity'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each receipt and its items
        foreach ($receiptsData as $receiptNumber => $receiptData) {
            $goodsReceipt = GoodsReceipt::where('receipt_number', $receiptNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($goodsReceipt) {
                // Update existing receipt
                $goodsReceipt->update($receiptData['receipt_data']);
                $goodsReceipt->items()->delete(); // Remove existing items to replace with new ones
            } else {
                // Create new receipt - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = GoodsReceipt::getEventDispatcher();
                GoodsReceipt::unsetEventDispatcher();

                try {
                    $goodsReceipt = new GoodsReceipt();
                    $goodsReceipt->forceFill($receiptData['receipt_data']);
                    $goodsReceipt->save();
                } finally {
                    // Re-enable model events
                    GoodsReceipt::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the receipt
            foreach ($receiptData['items'] as $itemData) {
                $itemData['goods_receipt_id'] = $goodsReceipt->id;
                GoodsReceiptItem::create($itemData);
            }

            // Refresh receipt tracking if there's a purchase order
            if ($goodsReceipt->purchase_order_id) {
                $goodsReceipt->purchaseOrder?->refreshReceiptTracking();
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'receipt_no' => isset($data['receipt_no']) ? (string) $data['receipt_no'] : null,
            'date' => isset($data['date']) ? (string) $data['date'] : null,
            'reference_no' => isset($data['reference_no']) ? (string) $data['reference_no'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'supplier_code' => isset($data['supplier_code']) ? (string) $data['supplier_code'] : null,
            'supplier_name' => isset($data['supplier_name']) ? (string) $data['supplier_name'] : null,
            'purchase_order_no' => isset($data['purchase_order_no']) ? (string) $data['purchase_order_no'] : null,
            'product_code' => isset($data['product_code']) ? (string) $data['product_code'] : null,
            'product_name' => isset($data['product_name']) ? (string) $data['product_name'] : null,
            'item_description' => isset($data['item_description']) ? (string) $data['item_description'] : null,
            'quantity' => isset($data['quantity']) ? (string) $data['quantity'] : null,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'receipt_no' => 'required|string|max:50',
            'date' => 'required',
            'reference_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'supplier_code' => 'nullable|string|max:50',
            'supplier_name' => 'nullable|string|max:255',
            'purchase_order_no' => 'nullable|string|max:100',
            'product_code' => 'required_without:product_name|string|max:50',
            'product_name' => 'required_without:product_code|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0',
            'unit_code' => 'nullable|string|max:20',
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
            'receipt_no.required' => 'Goods Receipt Number is required.',
            'receipt_no.max' => 'Goods Receipt Number cannot exceed 50 characters.',
            'date.required' => 'Date is required.',
            'reference_no.max' => 'Reference Number cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'supplier_code.max' => 'Supplier Code cannot exceed 50 characters.',
            'supplier_name.max' => 'Supplier Name cannot exceed 255 characters.',
            'purchase_order_no.max' => 'Purchase Order Number cannot exceed 100 characters.',
            'product_code.max' => 'Product Code cannot exceed 50 characters.',
            'product_name.max' => 'Product Name cannot exceed 255 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity cannot be less than 0.',
            'quantity.numeric' => 'Quantity must be a number.',
            'unit_code.max' => 'Unit Code cannot exceed 20 characters.',
        ];
    }
}
