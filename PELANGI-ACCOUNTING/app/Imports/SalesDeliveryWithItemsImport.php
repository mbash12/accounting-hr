<?php

namespace App\Imports;

use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Project;
use App\Models\SalesOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesDeliveryWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by delivery number to process deliveries and their items together
        $deliveriesData = [];

        foreach ($rows as $row) {
            $deliveryNumber = (string) $row['delivery_no'];

            if (!isset($deliveriesData[$deliveryNumber])) {
                // Create the delivery data
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
                    throw new \Exception("Either Customer Code or Customer Name is required for delivery {$deliveryNumber}");
                }

                $salesOrderId = null;
                if (!empty($row['sales_order_no'])) {
                    $salesOrder = SalesOrder::where('order_number', (string) $row['sales_order_no'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$salesOrder) {
                        throw new \Exception("Sales Order with number '{$row['sales_order_no']}' not found in current company for delivery {$deliveryNumber}");
                    }
                    $salesOrderId = $salesOrder->id;
                }

                $projectId = null;
                if (!empty($row['project_code'])) {
                    $project = Project::where('project_code', (string) $row['project_code'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$project) {
                        throw new \Exception("Project with code '{$row['project_code']}' not found in current company for delivery {$deliveryNumber}");
                    }
                    $projectId = $project->id;
                }

                $deliveriesData[$deliveryNumber] = [
                    'delivery_data' => [
                        'delivery_number' => $deliveryNumber,
                        'date' => isset($row['date']) ? $this->parseDate($row['date']) : now()->format('Y-m-d'),
                        'delivery_type' => isset($row['delivery_type']) ? (string) $row['delivery_type'] : 'goods',
                        'reference_no' => isset($row['reference_no']) ? (string) $row['reference_no'] : null,
                        'description' => isset($row['description']) ? (string) $row['description'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'sales_order_id' => $salesOrderId,
                        'job_id' => $projectId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this delivery
            $productId = null;
            if (!empty($row['product_code'])) {
                $product = Product::where('code', (string) $row['product_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['product_code']}' not found in current company for delivery {$deliveryNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['product_name'])) {
                $product = Product::where('name', (string) $row['product_name'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['product_name']}' not found in current company for delivery {$deliveryNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Product Code or Product Name is required for delivery {$deliveryNumber}");
            }

            $unitId = null;
            if (!empty($row['unit_code'])) {
                $unit = Unit::where('code', (string) $row['unit_code'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['unit_code']}' not found in current company for delivery {$deliveryNumber}");
                }
                $unitId = $unit->id;
            }

            $deliveriesData[$deliveryNumber]['items'][] = [
                'description' => isset($row['item_description']) ? (string) $row['item_description'] : null,
                'quantity' => isset($row['quantity']) ? (float) $row['quantity'] : 0,
                'unit_price' => isset($row['unit_price']) ? (float) $row['unit_price'] : 0,
                'total' => isset($row['item_total']) ? (float) $row['item_total'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each delivery and its items
        foreach ($deliveriesData as $deliveryNumber => $deliveryData) {
            $deliveryDocument = DeliveryDocument::where('delivery_number', $deliveryNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($deliveryDocument) {
                // Update existing delivery - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = DeliveryDocument::getEventDispatcher();
                DeliveryDocument::unsetEventDispatcher();

                try {
                    $deliveryDocument->update($deliveryData['delivery_data']);
                    $deliveryDocument->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    DeliveryDocument::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new delivery - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = DeliveryDocument::getEventDispatcher();
                DeliveryDocument::unsetEventDispatcher();

                try {
                    $deliveryDocument = new DeliveryDocument();
                    $deliveryDocument->forceFill($deliveryData['delivery_data']);
                    $deliveryDocument->save();
                } finally {
                    // Re-enable model events
                    DeliveryDocument::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the delivery
            foreach ($deliveryData['items'] as $itemData) {
                $itemData['delivery_document_id'] = $deliveryDocument->id;
                DeliveryDocumentItem::create($itemData);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'delivery_no' => isset($data['delivery_no']) ? (string) $data['delivery_no'] : null,
            'date' => isset($data['date']) ? (string) $data['date'] : null,
            'delivery_type' => isset($data['delivery_type']) ? (string) $data['delivery_type'] : null,
            'reference_no' => isset($data['reference_no']) ? (string) $data['reference_no'] : null,
            'description' => isset($data['description']) ? (string) $data['description'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'customer_code' => isset($data['customer_code']) ? (string) $data['customer_code'] : null,
            'customer_name' => isset($data['customer_name']) ? (string) $data['customer_name'] : null,
            'sales_order_no' => isset($data['sales_order_no']) ? (string) $data['sales_order_no'] : null,
            'project_code' => isset($data['project_code']) ? (string) $data['project_code'] : null,
            'product_code' => isset($data['product_code']) ? (string) $data['product_code'] : null,
            'product_name' => isset($data['product_name']) ? (string) $data['product_name'] : null,
            'item_description' => isset($data['item_description']) ? (string) $data['item_description'] : null,
            'quantity' => isset($data['quantity']) ? (string) $data['quantity'] : null,
            'unit_price' => isset($data['unit_price']) ? (string) $data['unit_price'] : null,
            'item_total' => isset($data['item_total']) ? (string) $data['item_total'] : null,
            'unit_code' => isset($data['unit_code']) ? (string) $data['unit_code'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'delivery_no' => 'required|string|max:50',
            'date' => 'required',
            'delivery_type' => 'nullable|in:goods,document|max:20',
            'reference_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'customer_code' => 'nullable|string|max:50',
            'customer_name' => 'nullable|string|max:255',
            'sales_order_no' => 'nullable|string|max:100',
            'project_code' => 'nullable|string|max:50',
            'product_code' => 'required_without:product_name|string|max:50',
            'product_name' => 'required_without:product_code|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'item_total' => 'nullable|numeric|min:0',
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
            'delivery_no.required' => 'Delivery Number is required.',
            'delivery_no.max' => 'Delivery Number cannot exceed 50 characters.',
            'date.required' => 'Date is required.',
            'delivery_type.in' => 'Type must be goods or document.',
            'reference_no.max' => 'Reference Number cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'customer_code.max' => 'Customer Code cannot exceed 50 characters.',
            'customer_name.max' => 'Customer Name cannot exceed 255 characters.',
            'sales_order_no.max' => 'Sales Order Number cannot exceed 100 characters.',
            'project_code.max' => 'Project Code cannot exceed 50 characters.',
            'product_code.max' => 'Product Code cannot exceed 50 characters.',
            'product_name.max' => 'Product Name cannot exceed 255 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity cannot be less than 0.',
            'quantity.numeric' => 'Quantity must be a number.',
            'unit_price.min' => 'Unit Price cannot be less than 0.',
            'unit_price.numeric' => 'Unit Price must be a number.',
            'item_total.min' => 'Item Total cannot be less than 0.',
            'item_total.numeric' => 'Item Total must be a number.',
            'unit_code.max' => 'Unit Code cannot exceed 20 characters.',
        ];
    }
}
