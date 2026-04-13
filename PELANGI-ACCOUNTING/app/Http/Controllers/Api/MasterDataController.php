<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MasterDataController extends Controller
{
    /**
     * Apply case-insensitive search filter to a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $search
     * @param  array  $columns  Columns to search against (ILIKE / LOWER LIKE)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySearch($query, ?string $search, array $columns)
    {
        if (empty($search)) {
            return $query;
        }

        $search = mb_strtolower(trim($search));

        return $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                $q->{$method}("LOWER({$column}) LIKE ?", ["%{$search}%"]);
            }
        });
    }

    public function coa(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Account::where('company_id', $request->company_id);
        $query = $this->applySearch($query, $request->search, ['code', 'name']);

        $data = $query->orderBy('code')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get account success',
            'data'    => $data,
        ]);
    }

    public function vendor(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Contact::whereRaw('is_supplier is true')
            ->where('company_id', $request->company_id)
            ->with('paymentTerm');

        $query = $this->applySearch($query, $request->search, ['name', 'contact_code', 'email', 'phone']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get vendor success',
            'data'    => $data,
        ]);
    }

    public function itemmaster(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Product::where('company_id', $request->company_id)
            ->with('unit', 'productGroup', 'supplier', 'tax');

        $query = $this->applySearch($query, $request->search, ['name', 'code', 'description']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get item master success',
            'data'    => $data,
        ]);
    }

    public function categories(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\ProductGroup::where('company_id', $request->company_id);
        $query = $this->applySearch($query, $request->search, ['name', 'code']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get categories success',
            'data'    => $data,
        ]);
    }

    public function department(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Department::where('company_id', $request->company_id);
        $query = $this->applySearch($query, $request->search, ['name', 'code']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get department success',
            'data'    => $data,
        ]);
    }

    public function unit(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Unit::where('company_id', $request->company_id);
        $query = $this->applySearch($query, $request->search, ['name', 'code', 'description']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get unit success',
            'data'    => $data,
        ]);
    }

    public function taxes(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Tax::where('company_id', $request->company_id);
        $query = $this->applySearch($query, $request->search, ['name', 'code']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get taxes success',
            'data'    => $data,
        ]);
    }

    public function customers(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Contact::where('is_customer', true)
            ->where('company_id', $request->company_id)
            ->where('is_active', true);

        $query = $this->applySearch($query, $request->search, ['name', 'contact_code', 'email', 'phone', 'contact_person']);

        $data = $query->select('id', 'contact_code', 'name', 'email', 'phone', 'contact_person')
            ->orderBy('name')
            ->limit(50)
            ->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get customers success',
            'data'    => $data,
        ]);
    }

    public function syncProducts(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }
        if (empty($request->code)) {
            return response()->json(['code' => 400, 'message' => 'code (SKU) is mandatory'], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $unitId = null;
            if ($request->unit_code) {
                $unit = \App\Models\Unit::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(code) = ?", [strtolower($request->unit_code)])
                    ->first();
                $unitId = $unit ? $unit->id : null;
            }

            $taxId = null;
            if ($request->tax_code) {
                $tax = \App\Models\Tax::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(code) = ?", [strtolower($request->tax_code)])
                    ->first();
                $taxId = $tax ? $tax->id : null;
            }

            $productGroupId = null;
            if ($request->product_group_code) {
                $pg = \App\Models\ProductGroup::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(code) = ?", [strtolower($request->product_group_code)])
                    ->first();
                $productGroupId = $pg ? $pg->id : null;
            }

            $supplierId = null;
            if ($request->supplier_code) {
                $supplier = \App\Models\Contact::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(contact_code) = ?", [strtolower($request->supplier_code)])
                    ->where('is_supplier', true)
                    ->first();
                $supplierId = $supplier ? $supplier->id : null;
            }

            $product = \App\Models\Product::where('company_id', $request->company_id)
                ->whereRaw("LOWER(code) = ?", [strtolower($request->code)])
                ->withTrashed()
                ->first();

            if (!$product) {
                $product = new \App\Models\Product();
                $product->company_id = $request->company_id;
                $product->code = $request->code;
            } else if (!$request->update_existing) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['code' => 200, 'message' => 'Product exists and update_existing is false', 'data' => $product]);
            }

            $product->name = $request->name ?? $product->name;
            $product->description = $request->description ?? $product->description;
            $product->cost_price = $request->cost_price ?? $product->cost_price;
            $product->selling_price = $request->selling_price ?? $product->selling_price;
            $product->min_order_qty = $request->min_order_qty ?? $product->min_order_qty;
            $product->created_by_user_id = 1;
            if (isset($request->is_active)) {
                $product->is_active = $request->is_active;
            }

            if ($unitId) $product->unit_id = $unitId;
            if ($taxId) $product->tax_id = $taxId;
            if ($productGroupId) $product->product_group_id = $productGroupId;
            if ($supplierId) $product->supplier_id = $supplierId;

            if ($request->hasFile('image')) {
                if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
                }
                $file = $request->file('image');
                $filename = $product->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('products', $filename, 'public');
                $product->image = $path;
            }

            $product->save();

            if (isset($request->is_active)) {
                if ($request->is_active == 0 && !$product->trashed()) {
                    $product->delete();
                } else if ($request->is_active == 1 && $product->trashed()) {
                    $product->restore();
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => 'Product synced successfully',
                'data'    => $product,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Product Sync Error: " . $e->getMessage());
            return response()->json(['code' => 500, 'message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}
