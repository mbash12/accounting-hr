<?php

namespace App\Imports;

use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesOrderWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by order number to process orders and their items together
        $ordersData = [];

        foreach ($rows as $row) {
            $orderNumber = (string) $row['order_no'];

            if (!isset($ordersData[$orderNumber])) {
                // Create the order data
                $customerId = null;
                if (!empty($row['customer_code'])) {
                    $customer = Contact::where('contact_code', (string) $row['customer_code'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                    if (!$customer) {
                        throw new \Exception("Customer with code '{$row['customer_code']}' not found or is not marked as customer in current company");
                    }
                    $customerId = $customer->id;
                } elseif (!empty($row['customer_name'])) {
                    $customer = Contact::where('name', (string) $row['customer_name'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                    if (!$customer) {
                        throw new \Exception("Customer with name '{$row['customer_name']}' not found or is not marked as customer in current company");
                    }
                    $customerId = $customer->id;
                } else {
                    throw new \Exception("Either Customer Code or Customer Name is required for order {$orderNumber}");
                }

                $ordersData[$orderNumber] = [
                    'order_data' => [
                        'order_number' => $orderNumber,
                        'date' => isset($row['date']) ? $this->parseDate($row['date']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['reference']) ? (string) $row['reference'] : null,
                        'description' => isset($row['order_description']) ? (string) $row['order_description'] : null,
                        'discount_percentage' => isset($row['discount_pct']) ? (float) $row['discount_pct'] : 0,
                        'other_charges' => isset($row['other_charges']) ? (float) $row['other_charges'] : 0,
                        'discount' => isset($row['discount']) ? (float) $row['discount'] : 0,
                        'subtotal' => isset($row['subtotal']) ? (float) $row['subtotal'] : 0,
                        'tax_amount' => isset($row['tax']) ? (float) $row['tax'] : 0,
                        'total_amount' => isset($row['total']) ? (float) $row['total'] : 0,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this order
            $productId = null;
            if (!empty($row['product_code'])) {
                $product = Product::where('code', (string) $row['product_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['product_code']}' not found in current company for order {$orderNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['product_name'])) {
                $product = Product::where('name', (string) $row['product_name'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['product_name']}' not found in current company for order {$orderNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Product Code or Product Name is required for order {$orderNumber}");
            }

            $unitId = null;
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company for order {$orderNumber}");
                }
                $unitId = $unit->id;
            }

            $taxId = null;
            if (!empty($row['tax_code'])) {
                $tax = Tax::where('code', (string) $row['tax_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$tax) {
                    throw new \Exception("Tax with code '{$row['tax_code']}' not found in current company for order {$orderNumber}");
                }
                $taxId = $tax->id;
            }

            $ordersData[$orderNumber]['items'][] = [
                'description' => isset($row['item_description']) ? (string) $row['item_description'] : null,
                'quantity' => isset($row['quantity']) ? (float) $row['quantity'] : 0,
                'unit_price' => isset($row['unit_price']) ? (float) $row['unit_price'] : 0,
                'total' => isset($row['item_total']) ? (float) $row['item_total'] : 0,
                'discount' => isset($row['item_discount']) ? (float) $row['item_discount'] : 0,
                'discount_percentage' => isset($row['item_discount_pct']) ? (float) $row['item_discount_pct'] : 0,
                'tax_amount' => isset($row['item_tax']) ? (float) $row['item_tax'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'tax_id' => $taxId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each order and its items
        foreach ($ordersData as $orderNumber => $orderData) {
            $salesOrder = SalesOrder::where('order_number', $orderNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($salesOrder) {
                // Update existing order - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesOrder::getEventDispatcher();
                SalesOrder::unsetEventDispatcher();

                try {
                    $salesOrder->update($orderData['order_data']);
                    $salesOrder->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    SalesOrder::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new order - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesOrder::getEventDispatcher();
                SalesOrder::unsetEventDispatcher();

                try {
                    $salesOrder = new SalesOrder();
                    $salesOrder->forceFill($orderData['order_data']);
                    $salesOrder->save();
                } finally {
                    // Re-enable model events
                    SalesOrder::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the order
            foreach ($orderData['items'] as $itemData) {
                $itemData['sales_order_id'] = $salesOrder->id;
                SalesOrderItem::create($itemData);
            }

            // Recalculate totals after all items are added
            $salesOrder->recalculateTotalsFromItems();

            // Temporarily disable model events again for the totals update to avoid journal creation for draft status
            $dispatcher = SalesOrder::getEventDispatcher();
            SalesOrder::unsetEventDispatcher();

            try {
                $salesOrder->saveQuietly();
            } finally {
                // Re-enable model events
                SalesOrder::setEventDispatcher($dispatcher);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'order_no' => isset($data['order_no']) ? (string) $data['order_no'] : null,
            'date' => isset($data['date']) ? (string) $data['date'] : null,
            'reference' => isset($data['reference']) ? (string) $data['reference'] : null,
            'order_description' => isset($data['order_description']) ? (string) $data['order_description'] : null,
            'discount_pct' => isset($data['discount_pct']) ? (string) $data['discount_pct'] : null,
            'other_charges' => isset($data['other_charges']) ? (string) $data['other_charges'] : null,
            'discount' => isset($data['discount']) ? (string) $data['discount'] : null,
            'subtotal' => isset($data['subtotal']) ? (string) $data['subtotal'] : null,
            'tax' => isset($data['tax']) ? (string) $data['tax'] : null,
            'total' => isset($data['total']) ? (string) $data['total'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'customer_code' => isset($data['customer_code']) ? (string) $data['customer_code'] : null,
            'customer_name' => isset($data['customer_name']) ? (string) $data['customer_name'] : null,
            'product_code' => isset($data['product_code']) ? (string) $data['product_code'] : null,
            'product_name' => isset($data['product_name']) ? (string) $data['product_name'] : null,
            'item_description' => isset($data['item_description']) ? (string) $data['item_description'] : null,
            'quantity' => isset($data['quantity']) ? (string) $data['quantity'] : null,
            'unit_price' => isset($data['unit_price']) ? (string) $data['unit_price'] : null,
            'item_total' => isset($data['item_total']) ? (string) $data['item_total'] : null,
            'item_discount' => isset($data['item_discount']) ? (string) $data['item_discount'] : null,
            'item_discount_pct' => isset($data['item_discount_pct']) ? (string) $data['item_discount_pct'] : null,
            'item_tax' => isset($data['item_tax']) ? (string) $data['item_tax'] : null,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
            'tax_code' => isset($data['tax_code']) ? (string) $data['tax_code'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'order_no' => 'required|string|max:50',
            'date' => 'required',
            'reference' => 'nullable|string|max:100',
            'order_description' => 'nullable|string|max:1000',
            'discount_pct' => 'nullable|numeric|min:0|max:100',
            'other_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'subtotal' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,posted',
            'customer_code' => 'nullable|string|max:50',
            'customer_name' => 'nullable|string|max:255',
            'product_code' => 'required_without:product_name|string|max:50',
            'product_name' => 'required_without:product_code|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'item_total' => 'nullable|numeric|min:0',
            'item_discount' => 'nullable|numeric|min:0',
            'item_discount_pct' => 'nullable|numeric|min:0|max:100',
            'item_tax' => 'nullable|numeric|min:0',
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
            'order_no.required' => 'Order Number is required.',
            'order_no.max' => 'Order Number cannot exceed 50 characters.',
            'date.required' => 'Date is required.',
            'reference.max' => 'Reference cannot exceed 100 characters.',
            'order_description.max' => 'Description cannot exceed 1000 characters.',
            'discount_pct.min' => 'Discount Percentage cannot be less than 0.',
            'discount_pct.max' => 'Discount Percentage cannot exceed 100.',
            'discount_pct.numeric' => 'Discount Percentage must be a number.',
            'other_charges.min' => 'Other Charges cannot be less than 0.',
            'other_charges.numeric' => 'Other Charges must be a number.',
            'discount.min' => 'Discount cannot be less than 0.',
            'discount.numeric' => 'Discount must be a number.',
            'subtotal.min' => 'Subtotal cannot be less than 0.',
            'subtotal.numeric' => 'Subtotal must be a number.',
            'tax.min' => 'Tax cannot be less than 0.',
            'tax.numeric' => 'Tax must be a number.',
            'total.min' => 'Total cannot be less than 0.',
            'total.numeric' => 'Total must be a number.',
            'customer_code.max' => 'Customer Code cannot exceed 50 characters.',
            'customer_name.max' => 'Customer Name cannot exceed 255 characters.',
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
            'item_discount.min' => 'Item Discount cannot be less than 0.',
            'item_discount.numeric' => 'Item Discount must be a number.',
            'item_discount_pct.min' => 'Item Discount Percentage cannot be less than 0.',
            'item_discount_pct.max' => 'Item Discount Percentage cannot exceed 100.',
            'item_discount_pct.numeric' => 'Item Discount Percentage must be a number.',
            'item_tax.min' => 'Item Tax cannot be less than 0.',
            'item_tax.numeric' => 'Item Tax must be a number.',
            'unit_code.max' => 'Unit Code cannot exceed 20 characters.',
            'tax_code.max' => 'Tax Code cannot exceed 50 characters.',
        ];
    }
}
