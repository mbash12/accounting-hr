<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    /**
     * Receive and create/update Sales Order from Inventory system
     * 
     * For NON-DEPOSIT: Single SO with all projects grouped
     * For DEPOSIT: Separate SO for each project (identified by is_grouped flag)
     */
    public function sync(Request $request)
    {
        $post = $request->all();
        
        $rules = [
            'order_number' => 'required|string',
            'order_type' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string',
            'client_po_number' => 'nullable|string',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'other_charges' => 'nullable|numeric',
            'status' => 'required|string',
            'job_number' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_code' => 'nullable|string',
            'company_id' => 'nullable|integer',
            'is_grouped' => 'nullable|boolean',
            'project_count' => 'nullable|integer',
            'parent_deposit_so_id' => 'nullable|integer',
            'parent_deposit_so_number' => 'nullable|string',
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'items.*.description' => 'nullable|string',
            'items.*.product_code' => 'nullable|string',
            'items.*.product_name' => 'nullable|string',
            'items.*.item_name' => 'nullable|string',
            'items.*.uom_code' => 'nullable|string',
            'items.*.tax_code' => 'nullable|string',
            'items.*.project_type' => 'nullable|string',
            'items.*.is_production' => 'nullable|boolean',
        ];

        $validator = Validator::make($post, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            Log::error('Sales Order sync validation failed', ['errors' => $errors]);
            return response()->json([
                'code' => 400,
                'message' => 'Invalid input',
                'data' => $errors,
            ], 400);
        }

        $companyId = $request->company_id ?? 1;
        
        // Enable query logging
        DB::enableQueryLog();
        
        try {
            // Find or create customer in separate transaction
            $customer = DB::transaction(function () use ($request, $companyId) {
                return $this->findOrCreateCustomer($request, $companyId);
            });
            
            if (!$customer) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Failed to find or create customer',
                ], 400);
            }

            // Generate order number before transaction
            $codeGenerator = app(\App\Services\CodeGeneratorService::class);
            $generatedOrderNumber = $codeGenerator->generateCode('sales_order', $companyId);

            DB::beginTransaction();
            
            Log::info('Starting sales order lookup', ['job_number' => $request->job_number, 'company_id' => $companyId]);
            
            // Check if Sales Order already exists by job_number
            $salesOrder = null;
            if ($request->job_number) {
                $salesOrder = SalesOrder::where('job_number', $request->job_number)
                    ->where('company_id', $companyId)
                    ->first();
            }
            
            Log::info('Sales order lookup complete', ['found' => $salesOrder ? 'yes' : 'no']);
            
            $mode = $salesOrder ? 'update' : 'create';
            
            if (!$salesOrder) {
                $salesOrder = new SalesOrder();
                $salesOrder->company_id = $companyId;
                $salesOrder->order_number = $generatedOrderNumber;
            }

            Log::info('Setting sales order data', [
                'client_po_number_from_request' => $request->client_po_number,
                'reference_no_from_request' => $request->reference_no,
                'all_request_keys' => array_keys($request->all()),
            ]);
            
            // Set Sales Order data
            // Map order_type from external system to internal values
            $orderTypeMap = [
                'sales_order' => 'standar',
                'deposit' => 'deposit',
                'standar' => 'standar',
                'aktual' => 'aktual',
            ];
            $orderType = $request->order_type ?? 'sales_order';
            $salesOrder->order_type = $orderTypeMap[$orderType] ?? 'standar';
            $salesOrder->date = $request->date;
            $salesOrder->reference_no = null;
            $salesOrder->client_po_number = $request->client_po_number;
            $salesOrder->description = null;
            
            Log::info('Sales order data assigned', [
                'client_po_number_assigned' => $salesOrder->client_po_number,
            ]);
            $salesOrder->subtotal = $request->subtotal;
            $salesOrder->tax_amount = $request->tax_amount ?? 0;
            $salesOrder->total_amount = $request->total_amount;
            $salesOrder->discount = $request->discount ?? 0;
            $salesOrder->discount_percentage = $request->discount_percentage ?? 0;
            $salesOrder->other_charges = $request->other_charges ?? 0;
            // Only set status to 'draft' when creating new SO, preserve existing status when updating
            if ($mode === 'create') {
                $salesOrder->status = 'draft';
            }
            $salesOrder->job_number = $request->job_number;
            $salesOrder->customer_id = $customer->id;
            $salesOrder->created_by_user_id = $request->created_by_user_id ?? 1;
            $salesOrder->is_closed = false;
            
            // Link to parent deposit SO if provided (for actual SOs linked to deposit SOs)
            if ($request->parent_deposit_so_id) {
                $salesOrder->related_order_id = $request->parent_deposit_so_id;
                Log::info('Linking actual SO to parent deposit SO', [
                    'actual_so_job_number' => $request->job_number,
                    'parent_deposit_so_id' => $request->parent_deposit_so_id,
                    'parent_deposit_so_number' => $request->parent_deposit_so_number,
                ]);
            }
            
            // Save delivery_meta and invoice_meta as empty arrays initially
            $salesOrder->delivery_meta = [
                'goods' => ['total' => 0, 'delivered' => 0, 'remaining' => 0],
                'document' => ['total' => 0, 'delivered' => 0, 'remaining' => 0],
            ];
            $salesOrder->invoice_meta = [
                'total' => 0,
                'invoiced' => 0,
                'remaining' => 0,
            ];
            
            Log::info('About to save sales order');
            
            // Use saveQuietly() to bypass model events (auto-generation, etc)
            $salesOrder->saveQuietly();
            
            Log::info('Sales order saved', ['id' => $salesOrder->id, 'order_number' => $salesOrder->order_number]);

            // Clear existing items if updating
            if ($mode === 'update') {
                $salesOrder->items()->delete();
            }

            // Create Sales Order Items
            foreach ($post['items'] as $itemData) {
                $this->createSalesOrderItem($salesOrder, $itemData, $companyId);
            }

            // Refresh delivery and invoice tracking
            $salesOrder->refreshDeliveryTracking();
            $salesOrder->refreshInvoiceTracking();

            DB::commit();

            Log::info('Sales Order synced successfully', [
                'mode' => $mode,
                'order_number' => $salesOrder->order_number,
                'sales_order_id' => $salesOrder->id,
                'is_grouped' => $request->is_grouped,
                'project_count' => $request->project_count,
                'related_order_id' => $salesOrder->related_order_id,
            ]);

            $responseData = [
                'sales_order_id' => $salesOrder->id,
                'order_number' => $salesOrder->order_number,
                'mode' => $mode,
                'is_grouped' => $request->is_grouped,
                'total_amount' => $salesOrder->total_amount,
            ];
            
            // Include parent deposit SO info if present
            if ($salesOrder->related_order_id) {
                $responseData['parent_deposit_so_id'] = $salesOrder->related_order_id;
                $parentSo = SalesOrder::find($salesOrder->related_order_id);
                if ($parentSo) {
                    $responseData['parent_deposit_so_number'] = $parentSo->order_number;
                }
            }

            return response()->json([
                'code' => 200,
                'message' => 'Sales Order synced successfully',
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log all queries that were executed
            $queries = DB::getQueryLog();
            Log::error('Sales Order sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'queries' => $queries
            ]);
            
            return response()->json([
                'code' => 500,
                'message' => 'Failed to sync Sales Order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find existing customer or create new one
     */
    private function findOrCreateCustomer(Request $request, int $companyId)
    {
        $customer = null;
        
        // Try to find by code first
        if ($request->customer_code) {
            $customer = Contact::where('contact_code', $request->customer_code)
                ->where('company_id', $companyId)
                ->where('is_customer', true)
                ->first();
        }
        
        // Try to find by name
        if (!$customer && $request->customer_name) {
            $customer = Contact::where('name', $request->customer_name)
                ->where('company_id', $companyId)
                ->where('is_customer', true)
                ->first();
        }
        
        // Create new customer if not found
        if (!$customer && $request->customer_name) {
            $customer = new Contact();
            $customer->name = $request->customer_name;
            $customer->contact_code = $request->customer_code ?? $this->generateCustomerCode($companyId);
            $customer->company_id = $companyId;
            $customer->is_customer = true;
            $customer->is_active = true;
            $customer->created_by_user_id = 1;
            $customer->save();
        }
        
        return $customer;
    }

    /**
     * Generate a unique customer code
     */
    private function generateCustomerCode(int $companyId): string
    {
        $prefix = 'CUST-';
        $random = strtoupper(uniqid());
        $code = $prefix . $random;
        
        // Ensure uniqueness
        while (Contact::where('contact_code', $code)->where('company_id', $companyId)->exists()) {
            $random = strtoupper(uniqid());
            $code = $prefix . $random;
        }
        
        return $code;
    }

    /**
     * Create a Sales Order Item
     */
    private function createSalesOrderItem(SalesOrder $salesOrder, array $itemData, int $companyId)
    {
        // Find or create product
        $product = $this->findOrCreateProduct($itemData, $companyId);
        
        // Find unit
        $unit = null;
        if (!empty($itemData['uom_code'])) {
            $unit = Unit::where('code', $itemData['uom_code'])
                ->where('company_id', $companyId)
                ->first();
        }
        
        // Find tax
        $tax = null;
        if (!empty($itemData['tax_code'])) {
            $tax = Tax::where('code', $itemData['tax_code'])
                ->where('company_id', $companyId)
                ->first();
        }

        // Get tax_id, fallback to product tax_id, then to default tax
        $taxId = optional($tax)->id ?? optional($product)->tax_id;
        if (!$taxId) {
            // Get or create default tax (11% PPN)
            $defaultTax = $this->getOrCreateDefaultTax($companyId);
            $taxId = $defaultTax?->id;
        }

        $item = new SalesOrderItem();
        $item->sales_order_id = $salesOrder->id;
        $item->quantity = $itemData['quantity'];
        $item->unit_price = $itemData['unit_price'];
        $item->total = $itemData['total'];
        $item->description = $itemData['description'];
        $item->item_name = $itemData['item_name'] ?? $itemData['product_name'] ?? null;
        $item->product_id = optional($product)->id;
        $item->unit_id = optional($unit)->id;
        $item->tax_id = $taxId;
        $item->discount = $itemData['discount'] ?? 0;
        $item->discount_percentage = $itemData['discount_percentage'] ?? 0;
        $item->tax_amount = $itemData['tax_amount'] ?? 0;

        // Set is_production: follow inventory way (is_production is the source of truth)
        $item->is_production = (bool)($itemData['is_production'] ?? true);

        $item->delivered_quantity = 0;
        $item->invoiced_quantity = 0;
        $item->save();

        return $item;
    }

    /**
     * Get or create default tax for the company
     */
    private function getOrCreateDefaultTax(int $companyId): ?Tax
    {
        // Try to find existing tax with code 'PPN' or '.'
        $tax = Tax::where('company_id', $companyId)
            ->whereIn('code', ['PPN', '.'])
            ->first();

        if ($tax) {
            return $tax;
        }

        // Create default PPN tax (11%)
        try {
            $tax = new Tax();
            $tax->name = 'Pajak Pertambahan Nilai';
            $tax->code = 'PPN';
            $tax->tax_percentage = 11.00;
            $tax->company_id = $companyId;
            $tax->is_active = true;
            $tax->is_sales_tax = true;
            $tax->is_purchase_tax = true;
            $tax->created_by_user_id = 1;
            $tax->save();

            Log::info('Created default tax for company', ['company_id' => $companyId, 'tax_id' => $tax->id]);
            return $tax;
        } catch (\Exception $e) {
            Log::error('Failed to create default tax', ['error' => $e->getMessage()]);
            // Last resort: get any tax from the company
            return Tax::where('company_id', $companyId)->first();
        }
    }

    /**
     * Find existing product or create new one
     * Search by product_code first (from inventory), then by name as fallback
     */
    private function findOrCreateProduct(array $itemData, int $companyId)
    {
        $product = null;
        $productCode = $itemData['product_code'] ?? null;
        $productName = $itemData['product_name'] ?? $itemData['description'];

        // Try to find by code first (from inventory product_code)
        if ($productCode) {
            $product = Product::where('code', $productCode)
                ->where('company_id', $companyId)
                ->first();
        }

        // Fallback: try to find by name if code not found
        if (!$product && $productName) {
            $product = Product::where('name', $productName)
                ->where('company_id', $companyId)
                ->first();
        }

        // Create new product if not found
        if (!$product && $productName) {
            $product = new Product();
            $product->name = $productName; // Use custom item name as product name

            // Use product_code from inventory if provided, otherwise generate
            if ($productCode) {
                $product->code = $productCode;
            } else {
                // Use document numbering service for product code
                $codeGenerator = app(\App\Services\CodeGeneratorService::class);
                $product->code = $codeGenerator->generateCode('product', $companyId) ?: $this->generateProductCodeFallback($companyId);
            }
            
            $product->description = $itemData['description'];
            $product->company_id = $companyId;
            
            // Set product type based on is_production (follow inventory way)
            $isProduction = (bool)($itemData['is_production'] ?? true);
            $product->product_type = $isProduction ? 'good' : 'service';
            
            $product->selling_price = $itemData['unit_price'] ?? 0;
            $product->cost_price = $itemData['unit_price'] ?? 0;
            $product->is_active = true;
            $product->created_by_user_id = 1;
            
            // Find or create unit based on uom_code from inventory
            if (!empty($itemData['uom_code'])) {
                $unit = $this->findOrCreateUnit($itemData['uom_code'], $companyId);
                $product->unit_id = optional($unit)->id;
            } else {
                // Fallback to default unit
                $defaultUnit = Unit::where('company_id', $companyId)->first();
                $product->unit_id = optional($defaultUnit)->id;
            }
            
            // Find or create tax by code if provided
            if (!empty($itemData['tax_code'])) {
                $tax = $this->findOrCreateTax($itemData['tax_code'], $companyId);
                $product->tax_id = optional($tax)->id;
            }
            
            // Set product category based on project_type
            if (!empty($itemData['project_type'])) {
                $productGroup = $this->findOrCreateProductGroup($itemData['project_type'], $companyId, $isProduction);
                if ($productGroup) {
                    $product->product_group_id = $productGroup->id;
                }
            }
            
            $product->save();
        }
        
        return $product;
    }
    
    /**
     * Find or create unit by code
     */
    private function findOrCreateUnit(string $uomCode, int $companyId)
    {
        // Try to find by code (case-insensitive)
        $unit = Unit::whereRaw('LOWER(code) = ?', [strtolower($uomCode)])
            ->where('company_id', $companyId)
            ->first();
        
        // Create new unit if not found
        if (!$unit) {
            $unit = new Unit();
            $unit->name = strtoupper($uomCode);
            $unit->code = strtoupper($uomCode);
            $unit->company_id = $companyId;
            $unit->is_active = true;
            $unit->created_by_user_id = 1;
            $unit->save();
        }
        
        return $unit;
    }
    
    /**
     * Find or create tax by code
     */
    private function findOrCreateTax(string $taxCode, int $companyId)
    {
        // Try to find by code (case-insensitive)
        $tax = Tax::whereRaw('LOWER(code) = ?', [strtolower($taxCode)])
            ->where('company_id', $companyId)
            ->first();
        
        // Create new tax if not found
        if (!$tax) {
            $tax = new Tax();
            $tax->name = 'Tax ' . strtoupper($taxCode);
            $tax->code = strtoupper($taxCode);
            $tax->tax_percentage = 11.00; // Default 11% VAT
            $tax->company_id = $companyId;
            $tax->is_active = true;
            $tax->created_by_user_id = 1;
            $tax->save();
        }
        
        return $tax;
    }
    
    /**
     * Find existing product group by name or create new one
     */
    private function findOrCreateProductGroup(string $name, int $companyId, bool $isProduction = true)
    {
        // Try to find by name (case-insensitive)
        $productGroup = ProductGroup::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->where('company_id', $companyId)
            ->first();
        
        // Create new product group if not found
        if (!$productGroup) {
            $productGroup = new ProductGroup();
            $productGroup->name = ucfirst($name);
            $productGroup->code = $this->generateProductGroupCode($name, $companyId);
            $productGroup->company_id = $companyId;
            $productGroup->is_active = true;
            $productGroup->created_by_user_id = 1;
            // Set shipping_type based on isProduction (follow inventory way)
            $productGroup->shipping_type = $isProduction ? 'physical' : 'digital';
            $productGroup->save();
        }
        
        return $productGroup;
    }
    
    /**
     * Generate a unique product group code
     */
    private function generateProductGroupCode(string $name, int $companyId): string
    {
        // Create code from first 3 letters of name + random
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 3)) ?: 'PG';
        $random = strtoupper(substr(uniqid(), -4));
        $code = $prefix . '-' . $random;
        
        // Ensure uniqueness
        while (ProductGroup::where('code', $code)->where('company_id', $companyId)->exists()) {
            $random = strtoupper(substr(uniqid(), -4));
            $code = $prefix . '-' . $random;
        }
        
        return $code;
    }

    /**
     * Fallback method to generate a unique product code if document numbering fails
     */
    private function generateProductCodeFallback(int $companyId): string
    {
        $prefix = 'PROD-';
        $random = strtoupper(uniqid());
        $code = $prefix . $random;
        
        // Ensure uniqueness
        while (Product::where('code', $code)->where('company_id', $companyId)->exists()) {
            $random = strtoupper(uniqid());
            $code = $prefix . $random;
        }
        
        return $code;
    }

    /**
     * List Sales Orders with optional filters
     * For e-procurement integration
     */
    public function list(Request $request)
    {
        $companyId = $request->company_id ?? 1;

        try {
            $query = SalesOrder::with(['customer', 'items.product', 'items.unit'])
                ->where('company_id', $companyId)
                ->where('status', 'posted')
                ->whereIn('order_type', ['standar', 'aktual'])
                ->whereNotNull('job_number') // Only include records that have a job number
                // Only show SOs that have at least one item whose qty is not yet fully
                // covered by PO items (matched item-to-item by product_id)
                ->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('sales_order_items as soi')
                        ->whereColumn('soi.sales_order_id', 'sales_orders.id')
                        ->where('soi.is_production', true)
                        ->whereNull('soi.deleted_at')
                        ->whereRaw('soi.quantity > (
                            SELECT COALESCE(SUM(poi.quantity), 0)
                            FROM purchase_order_items poi
                            INNER JOIN purchase_orders po ON po.id = poi.purchase_order_id
                            WHERE po.sales_order_id = sales_orders.id
                              AND poi.product_id = soi.product_id
                              AND po.deleted_at IS NULL
                              AND poi.deleted_at IS NULL
                        )');
                });

            // Filter by job_number
            if ($request->has('job_number')) {
                $query->where('job_number', 'like', '%' . $request->job_number . '%');
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->has('date_until')) {
                $query->whereDate('date', '<=', $request->date_until);
            }

            // Pagination
            $perPage = $request->per_page ?? 20;
            $salesOrders = $query->orderBy('date', 'desc')
                ->paginate($perPage);

            // Transform data
            $data = $salesOrders->map(function ($so) {
                return [
                    'id' => $so->id,
                    'order_number' => $so->order_number,
                    'job_number' => $so->job_number,
                    'order_type' => $so->order_type,
                    'date' => $so->date->format('Y-m-d'),
                    'status' => $so->status,
                    'customer' => [
                        'id' => $so->customer?->id,
                        'name' => $so->customer?->name,
                        'code' => $so->customer?->contact_code,
                    ],
                    'total_amount' => $so->total_amount,
                    'subtotal' => $so->subtotal,
                    'tax_amount' => $so->tax_amount,
                    'discount' => $so->discount,
                    'other_charges' => $so->other_charges,
                    'description' => $so->description,
                    'created_at' => $so->created_at?->format('Y-m-d H:i:s'),
                    'updated_at' => $so->updated_at?->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $data,
                'pagination' => [
                    'current_page' => $salesOrders->currentPage(),
                    'last_page' => $salesOrders->lastPage(),
                    'per_page' => $salesOrders->perPage(),
                    'total' => $salesOrders->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Sales Order list failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Failed to get Sales Orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Sales Order detail by id, job_number or order_number
     * For e-procurement integration
     */
    public function detail(Request $request)
    {
        $rules = [
            'id' => 'nullable|integer',
            'job_number' => 'nullable|string',
            'order_number' => 'nullable|string',
            'company_id' => 'nullable|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid input',
                'data' => $validator->errors()->toArray(),
            ], 400);
        }

        if (!$request->id && !$request->job_number && !$request->order_number) {
            return response()->json([
                'code' => 400,
                'message' => 'Either id, job_number or order_number is required',
            ], 400);
        }

        $companyId = $request->company_id ?? 1;

        try {
            $query = SalesOrder::with(['customer', 'items.product', 'items.unit', 'items.tax'])
                ->where('company_id', $companyId);

            if ($request->id) {
                $query->where('id', $request->id);
            }

            if ($request->job_number) {
                $query->where('job_number', $request->job_number);
            }

            if ($request->order_number) {
                $query->where('order_number', $request->order_number);
            }

            $salesOrder = $query->first();

            if (!$salesOrder) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Sales Order not found',
                ], 404);
            }

            // Transform items
            $items = $salesOrder->items->where('is_production', true)->values()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'id' => $item->product?->id,
                        'name' => $item->product?->name,
                        'code' => $item->product?->code,
                    ],
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                    'unit' => [
                        'id' => $item->unit?->id,
                        'name' => $item->unit?->name,
                        'code' => $item->unit?->code,
                    ],
                    'tax' => [
                        'id' => $item->tax?->id,
                        'name' => $item->tax?->name,
                        'percentage' => $item->tax?->tax_percentage,
                    ],
                    'discount' => $item->discount,
                    'discount_percentage' => $item->discount_percentage,
                    'delivered_quantity' => $item->delivered_quantity,
                    'invoiced_quantity' => $item->invoiced_quantity,
                ];
            });

            $data = [
                'id' => $salesOrder->id,
                'order_number' => $salesOrder->order_number,
                'job_number' => $salesOrder->job_number,
                'order_type' => $salesOrder->order_type,
                'date' => $salesOrder->date->format('Y-m-d'),
                'status' => $salesOrder->status,
                'reference_no' => $salesOrder->reference_no,
                'description' => $salesOrder->description,
                'customer' => [
                    'id' => $salesOrder->customer?->id,
                    'name' => $salesOrder->customer?->name,
                    'code' => $salesOrder->customer?->contact_code,
                    'email' => $salesOrder->customer?->email,
                    'phone' => $salesOrder->customer?->phone,
                ],
                'financial' => [
                    'subtotal' => $salesOrder->subtotal,
                    'tax_amount' => $salesOrder->tax_amount,
                    'discount' => $salesOrder->discount,
                    'discount_percentage' => $salesOrder->discount_percentage,
                    'other_charges' => $salesOrder->other_charges,
                    'total_amount' => $salesOrder->total_amount,
                ],
                'delivery_meta' => $salesOrder->delivery_meta,
                'invoice_meta' => $salesOrder->invoice_meta,
                'items' => $items,
                'created_at' => $salesOrder->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $salesOrder->updated_at?->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            Log::error('Sales Order detail failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Failed to get Sales Order detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
