<?php

namespace App\Imports;

use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Project;
use App\Models\DeliveryDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesReturnWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by return number to process returns and their items together
        $returnsData = [];

        foreach ($rows as $row) {
            $returnNumber = (string) $row['return_no'];

            if (!isset($returnsData[$returnNumber])) {
                // Create the return data
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
                    throw new \Exception("Either Customer Code or Customer Name is required for return {$returnNumber}");
                }

                $deliveryDocumentId = null;
                if (!empty($row['delivery_no'])) {
                    $deliveryDocument = DeliveryDocument::where('delivery_number', (string) $row['delivery_no'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$deliveryDocument) {
                        throw new \Exception("Delivery Document with number '{$row['delivery_no']}' not found in current company for return {$returnNumber}");
                    }
                    $deliveryDocumentId = $deliveryDocument->id;
                }

                $returnsData[$returnNumber] = [
                    'return_data' => [
                        'return_number' => $returnNumber,
                        'date' => isset($row['date']) ? $this->parseDate($row['date']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['reference_no']) ? (string) $row['reference_no'] : null,
                        'description' => isset($row['description']) ? (string) $row['description'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'delivery_document_id' => $deliveryDocumentId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this return
            $productId = null;
            if (!empty($row['product_code'])) {
                $product = Product::where('code', (string) $row['product_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['product_code']}' not found in current company for return {$returnNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['product_name'])) {
                $product = Product::where('name', (string) $row['product_name'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['product_name']}' not found in current company for return {$returnNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Product Code or Product Name is required for return {$returnNumber}");
            }

            $unitId = null;
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company for return {$returnNumber}");
                }
                $unitId = $unit->id;
            }

            $returnsData[$returnNumber]['items'][] = [
                'description' => isset($row['item_description']) ? (string) $row['item_description'] : null,
                'quantity' => isset($row['quantity']) ? (float) $row['quantity'] : 0,
                'return_reason' => isset($row['return_reason']) ? (string) $row['return_reason'] : null,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each return and its items
        foreach ($returnsData as $returnNumber => $returnData) {
            $salesReturn = SalesReturn::where('return_number', $returnNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($salesReturn) {
                // Update existing return
                $salesReturn->update($returnData['return_data']);
                $salesReturn->items()->delete(); // Remove existing items to replace with new ones
            } else {
                // Create new return - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesReturn::getEventDispatcher();
                SalesReturn::unsetEventDispatcher();

                try {
                    $salesReturn = new SalesReturn();
                    $salesReturn->forceFill($returnData['return_data']);
                    $salesReturn->save();
                } finally {
                    // Re-enable model events
                    SalesReturn::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the return
            foreach ($returnData['items'] as $itemData) {
                $itemData['sales_return_id'] = $salesReturn->id;
                SalesReturnItem::create($itemData);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'return_no' => isset($data['return_no']) ? (string) $data['return_no'] : null,
            'date' => isset($data['date']) ? (string) $data['date'] : null,
            'reference_no' => isset($data['reference_no']) ? (string) $data['reference_no'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'customer_code' => isset($data['customer_code']) ? (string) $data['customer_code'] : null,
            'customer_name' => isset($data['customer_name']) ? (string) $data['customer_name'] : null,
            'delivery_no' => isset($data['delivery_no']) ? (string) $data['delivery_no'] : null,
            'product_code' => isset($data['product_code']) ? (string) $data['product_code'] : null,
            'product_name' => isset($data['product_name']) ? (string) $data['product_name'] : null,
            'item_description' => isset($data['item_description']) ? (string) $data['item_description'] : null,
            'quantity' => isset($data['quantity']) ? (string) $data['quantity'] : null,
            'return_reason' => isset($data['return_reason']) ? (string) $data['return_reason'] : null,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'return_no' => 'required|string|max:50',
            'date' => 'required',
            'reference_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'customer_code' => 'nullable|string|max:50',
            'customer_name' => 'nullable|string|max:255',
            'delivery_no' => 'nullable|string|max:100',
            'product_code' => 'required_without:product_name|string|max:50',
            'product_name' => 'required_without:product_code|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0',
            'return_reason' => 'required|string|max:255',
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
            'return_no.required' => 'Return Number is required.',
            'return_no.max' => 'Return Number cannot exceed 50 characters.',
            'date.required' => 'Date is required.',
            'reference_no.max' => 'Reference Number cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'customer_code.max' => 'Customer Code cannot exceed 50 characters.',
            'customer_name.max' => 'Customer Name cannot exceed 255 characters.',
            'delivery_no.max' => 'Delivery Number cannot exceed 100 characters.',
            'product_code.max' => 'Product Code cannot exceed 50 characters.',
            'product_name.max' => 'Product Name cannot exceed 255 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity cannot be less than 0.',
            'quantity.numeric' => 'Quantity must be a number.',
            'return_reason.required' => 'Return Reason is required.',
            'return_reason.max' => 'Return Reason cannot exceed 255 characters.',
            'unit_code.max' => 'Unit Code cannot exceed 20 characters.',
        ];
    }
}
