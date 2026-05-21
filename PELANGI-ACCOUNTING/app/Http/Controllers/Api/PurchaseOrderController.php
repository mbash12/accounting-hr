<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ProductGroup;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;
use Validator;
use DB;

class PurchaseOrderController extends Controller
{
    public function storePurchaseOrder(Request $request)
    {
        $post = $request->all();
        $rules = [];

        $rules['purchase_order_no'] = 'required';
        $rules['date'] = 'required';
        $rules['reference_no'] = ''; 
        $rules['description'] = 'required';
        // $rules['other_charges'] = 'required';
        // $rules['discount'] = 'required';
        $rules['subtotal'] = 'required';
        $rules['tax_amount'] = ''; 
        $rules['total'] = 'required';
        $rules['status'] = 'required';
        $rules['supplier_code'] = 'required';
        // $rules['job_id'] = 'required';
        // $rules['department_id'] = 'required';
        // $rules['other_charges_account_id'] = 'required';
        // $rules['discount_account_id'] = 'required';
        $rules['supplier_code'] = 'required';
        $rules['company_id'] = 'required';
        $rules['total_amount'] = 'required';
        // $rules['discount_percentage'] = 'required';
        
        $rules['items.*.quantity'] = 'required';
        $rules['items.*.unit_price'] = 'required';
        $rules['items.*.total'] = 'required';
        $rules['items.*.description'] = 'required';
        $rules['items.*.product_code'] = 'required';
        $rules['items.*.unit_code'] = '';

        $validator = Validator::make($post, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json([
                'code' => 400,
                'message' => 'Input tidak valid',
                'data' => $errors,
            ], 400);
        }

        $po = \App\Models\PurchaseOrder::where('purchase_order_no', $request->purchase_order_no)->where('company_id', $request->company_id)->first();
        if (!empty($po)) {
            $mode = 'replace';
        } else {
            $mode = 'create';
            $po = new \App\Models\PurchaseOrder;
            
            if ($request->purchase_order_no) {
                $po->purchase_order_no = $request->purchase_order_no;
            } else {
                $po->purchase_order_no = "AEPO" . rand(111111111111,999999999999);
                while (\App\Models\PurchaseOrder::isPoExist($po->purchase_order_no)) {
                    $po->purchase_order_no = "AEPO" . rand(111111111111,999999999999);
                }
            }
        }
        
        
        try {
            DB::beginTransaction();
            //code here

            $supplier = Contact::where('contact_code', $request->supplier_code)->where('company_id', $request->company_id)->first();

            $po->date = $request->date;
            $po->reference_no = $request->reference_no;
            $po->description = $request->description;
            $po->other_charges = $request->other_charges ?? 0;
            $po->discount = $request->discount ?? 0;
            $po->subtotal = $request->subtotal;
            $po->tax_amount = $request->tax_amount ?? 0;
            $po->total = $request->total;
            $po->status = $request->status ?: 'draft';
            $po->supplier_id = optional($supplier)->id;
            $po->other_charges_account_id = $request->other_charges_account_id;
            $po->discount_account_id = $request->discount_account_id;
            $po->company_id = $request->company_id ?: 1;
            $po->created_by_user_id = $request->created_by_user_id ?? 0;
            $po->updated_by_user_id = $request->updated_by_user_id;
            $po->is_closed = $request->is_closed ?? false;
            $po->valid_until = $request->valid_until;
            $po->total_amount = $request->total_amount ?: $request->total;
            $po->discount_percentage = $request->discount_percentage ?? 0;
            $po->save();

            if ($mode == 'replace') {
                foreach ($po->items as $itm) {
                    $itm->delete();
                } 
            }

            foreach ($post['items'] as $item) {
                $product = null;


                $product = \App\Models\Product::where('code', $item['product_code'])->where('company_id', $po->company_id)->first();
                $product_id = optional($product)->id;

                $unit = Unit::where('code', $item['unit_code'])->where('company_id', $po->company_id)->first();   
                $tax = Tax::where('code', $item['tax_code'])->where('company_id', $po->company_id)->first();
                $product_group = ProductGroup::where('code', $item['product_group_code'])->where('company_id', $po->company_id)->first();



                if (is_numeric($product_id)) {
                    $product = \App\Models\Product::find($product_id);
                }

                // if (!$product) {
                //     $product = \App\Models\Product::where('code', $product_id)->first();
                // }

                // if (!$product) {
                //     $product = \App\Models\Product::where('name', $product_id)->first();
                // }

                // if (!$product && isset($item['description'])) {
                //     $product = \App\Models\Product::where('name', $item['description'])->first();
                // }

                if (!$product) {

                    $product = new \App\Models\Product();
                    $product->name = $item['product_name'] ?? ($item['description'] ?? ($product_id ?: 'New Product'));
                    $product->code = $item['product_code'] ?? null;
                    $product->description = $item['product_description'] ?? ($item['description'] ?? null);
                    $product->company_id = $po->company_id;
                    $product->product_group_id = optional($product_group)->id;
                    $product->unit_id = optional($unit)->id;
                    $product->product_type = $item['product_type'] ?? 'good';
                    $product->tax_id = optional($tax)->id;
                    $product->cost_price = $item['cost_price'] ?? ($item['unit_price'] ?? 0);
                    $product->selling_price = $item['selling_price'] ?? ($item['unit_price'] ?? 0);
                    $product->is_active = $item['is_active'] ?? true;
                    $product->created_by_user_id = 1;
                    $product->save();
                    
                    $product->refresh();
                }

                // Get tax_id with fallback to default tax
                $taxId = optional($tax)->id ?: optional($product)->tax_id;
                if (!$taxId) {
                    $taxId = $this->getOrCreateDefaultTax($po->company_id)?->id;
                }

                $po_item = new \App\Models\PurchaseOrderItem;
                $po_item->quantity = $item['quantity'];
                $po_item->unit_price = $item['unit_price'];
                $po_item->total = $item['total'];
                $po_item->description = $item['description'];
                $po_item->purchase_order_id = $po->id;
                $po_item->product_id = optional($product)->id;
                $po_item->unit_id = optional($unit)->id;
                $po_item->tax_id = $taxId;
                $po_item->discount = $item['discount'] ?? 0;
                $po_item->discount_percentage = $item['discount_percentage'] ?? 0;
                $po_item->tax_amount = $item['tax_amount'] ?? 0;
                $po_item->save();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        
            return response()->json([
                'code' => 500,
                'message' => 'Major transaction error, data rolled back',
                'error' => $e->getMessage() 
            ], 500);
        }


        return response()->json([
            'code' => 200,
            'message' => 'submit po succecss',
            'data' => $po
        ]);
        
    }

    public function detailPurchaseOrder(Request $request)
    {
        $data = \App\Models\PurchaseOrder::where('purchase_order_no', $request->purchase_order_no)->first();

        if (empty($data)) {
            return response()->json([
                'code' => 400,
                'message' => 'invalid po code',
            ], 400);
        }


        return response()->json([
            'code' => 200,
            'message' => 'submit po succecss',
            'data' => $data
        ]);
        
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

            \Illuminate\Support\Facades\Log::info('Created default tax for company', ['company_id' => $companyId, 'tax_id' => $tax->id]);
            return $tax;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create default tax', ['error' => $e->getMessage()]);
            // Last resort: get any tax from the company
            return Tax::where('company_id', $companyId)->first();
        }
    }
}
