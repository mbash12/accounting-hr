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
            ->where('is_active', true)
            ->with('paymentTerm');

        $query = $this->applySearch($query, $request->search, ['name', 'contact_code', 'email', 'phone']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get vendor success',
            'data'    => $data,
        ]);
    }

    public function syncVendor(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        if (empty($request->name)) {
            return response()->json(['code' => 400, 'message' => 'name is mandatory'], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $paymentTermId = null;
            if ($request->payment_term_id) {
                $paymentTermId = $request->payment_term_id;
            } elseif ($request->payment_term_code) {
                $pt = \App\Models\PaymentTerm::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(code) = ?", [strtolower($request->payment_term_code)])
                    ->first();
                $paymentTermId = $pt ? $pt->id : null;
            }

            // Find existing contact by contact_code or name within company
            $contact = null;
            if (!empty($request->contact_code)) {
                $contact = \App\Models\Contact::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(contact_code) = ?", [strtolower($request->contact_code)])
                    ->withTrashed()
                    ->first();
            }

            if (!$contact) {
                $contact = \App\Models\Contact::where('company_id', $request->company_id)
                    ->whereRaw("LOWER(name) = ?", [strtolower($request->name)])
                    ->withTrashed()
                    ->first();
            }

            if (!$contact) {
                $contact = new \App\Models\Contact();
                $contact->company_id = $request->company_id;
                if (!empty($request->contact_code)) {
                    $contact->contact_code = $request->contact_code;
                }
            } else if (isset($request->update_existing) && !$request->update_existing) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['code' => 200, 'message' => 'Vendor exists and update_existing is false', 'data' => $contact]);
            }

            $contact->name = $request->name;
            $contact->is_supplier = true;
            if ($request->has('email')) $contact->email = $request->email;
            if ($request->has('phone')) $contact->phone = $request->phone;
            if ($request->has('contact_person')) $contact->contact_person = $request->contact_person;
            if ($request->has('tax')) $contact->tax = $request->tax;
            if ($request->has('is_pkp')) $contact->is_pkp = filter_var($request->is_pkp, FILTER_VALIDATE_BOOLEAN);
            if ($request->has('credit_limit')) $contact->credit_limit = $request->credit_limit;
            if ($request->has('billing_address_line_1')) $contact->billing_address_line_1 = $request->billing_address_line_1;
            if ($request->has('billing_address_line_2')) $contact->billing_address_line_2 = $request->billing_address_line_2;
            if ($request->has('billing_city')) $contact->billing_city = $request->billing_city;
            if ($request->has('billing_state')) $contact->billing_state = $request->billing_state;
            if ($request->has('billing_postal_code')) $contact->billing_postal_code = $request->billing_postal_code;
            if ($request->has('billing_country')) $contact->billing_country = $request->billing_country;

            if ($paymentTermId) {
                $contact->payment_term_id = $paymentTermId;
            }

            if (isset($request->is_active)) {
                $contact->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            } else {
                $contact->is_active = true;
            }

            $contact->save();

            if ($contact->trashed() && $contact->is_active) {
                $contact->restore();
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => 'Vendor synced successfully',
                'data'    => $contact,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Vendor Sync Error: " . $e->getMessage());
            return response()->json(['code' => 500, 'message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function itemmaster(Request $request)
    {
        if (empty($request->company_id)) {
            return response()->json(['code' => 400, 'message' => 'company_id is mandatory'], 400);
        }

        $query = \App\Models\Product::where('company_id', $request->company_id)
            ->with('unit', 'productGroup', 'tax');

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
            $product->created_by_user_id = 1;
            if (isset($request->is_active)) {
                $product->is_active = $request->is_active;
            }

            if ($unitId) $product->unit_id = $unitId;
            if ($taxId) $product->tax_id = $taxId;
            if ($productGroupId) $product->product_group_id = $productGroupId;

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

    public function companies(Request $request)
    {
        $query = \App\Models\Company::query();

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        } else {
            $query->where('is_active', true);
        }

        $query = $this->applySearch($query, $request->search, ['name', 'description']);

        $data = $query->orderBy('name')->get();

        return response()->json([
            'code'    => 200,
            'message' => 'get companies success',
            'data'    => $data,
        ]);
    }

    public function syncUom(Request $request)
    {
        $items = $request->json('data');
        if (empty($items) || !is_array($items)) {
            return response()->json(['code' => 400, 'message' => 'data (array of {code, name}) is required'], 400);
        }

        $companyId = $request->input('company_id');
        if (empty($companyId)) {
            return response()->json(['code' => 400, 'message' => 'company_id is required'], 400);
        }

        $results = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];

        \App\Services\WismaService::withoutUomSync(function () use ($items, $companyId, &$results) {
            foreach ($items as $item) {
                try {
                    $code = $item['code'] ?? null;
                    $name = $item['name'] ?? null;
                    $action = $item['action'] ?? null;

                    if (empty($code)) {
                        $results['errors'][] = 'Missing code';
                        $results['skipped']++;
                        continue;
                    }

                    if ($action === 'delete' || $action === 'deleted') {
                        $unit = \App\Models\Unit::whereRaw("LOWER(code) = ?", [strtolower($code)])
                            ->where('company_id', $companyId)
                            ->first();
                        if ($unit) {
                            $unit->forceDelete();
                            $results['updated']++;
                        } else {
                            $results['skipped']++;
                        }
                        continue;
                    }

                    $unit = \App\Models\Unit::whereRaw("LOWER(code) = ?", [strtolower($code)])
                        ->where('company_id', $companyId)
                        ->withTrashed()
                        ->first();

                    if (!$unit) {
                        $unit = new \App\Models\Unit();
                        $unit->code = $code;
                        $unit->company_id = $companyId;
                        $unit->created_by_user_id = 1;
                        $isNew = true;
                    } else {
                        if ($unit->trashed()) {
                            $unit->restore();
                        }
                        $isNew = false;
                    }

                    if ($name !== null) $unit->name = $name;
                    if (isset($item['description'])) $unit->description = $item['description'];
                    if (isset($item['is_active'])) $unit->is_active = $item['is_active'];
                    $unit->save();

                    $isNew ? $results['created']++ : $results['updated']++;
                } catch (\Throwable $e) {
                    $results['errors'][] = ($item['code'] ?? 'unknown') . ': ' . $e->getMessage();
                    $results['skipped']++;
                }
            }
        });

        return response()->json([
            'code' => 200,
            'message' => 'UOM sync completed',
            'data' => $results,
        ]);
    }

    public function syncUomCategories(Request $request)
    {
        $items = $request->json('data');
        if (empty($items) || !is_array($items)) {
            return response()->json(['code' => 400, 'message' => 'data (array of {code, name, base_uom_code}) is required'], 400);
        }

        $companyId = $request->input('company_id');
        if (empty($companyId)) {
            return response()->json(['code' => 400, 'message' => 'company_id is required'], 400);
        }

        $results = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];

        \App\Services\WismaService::withoutUomSync(function () use ($items, $companyId, &$results) {
            foreach ($items as $item) {
                try {
                    $code = $item['code'] ?? null;
                    $name = $item['name'] ?? null;
                    $baseUomCode = $item['base_uom_code'] ?? null;
                    $action = $item['action'] ?? null;

                    if ($action === 'delete' || $action === 'deleted') {
                        $category = \App\Models\UnitCategory::whereRaw("LOWER(code) = ?", [strtolower($code)])
                            ->where('company_id', $companyId)
                            ->first();
                        if ($category) {
                            $category->forceDelete();
                            $results['updated']++;
                        } else {
                            $results['skipped']++;
                        }
                        continue;
                    }

                    if (empty($code) || empty($name)) {
                        $results['errors'][] = 'Missing code or name';
                        $results['skipped']++;
                        continue;
                    }

                    $baseUnitId = null;
                    if ($baseUomCode) {
                        $baseUnit = \App\Models\Unit::whereRaw("LOWER(code) = ?", [strtolower($baseUomCode)])
                            ->where('company_id', $companyId)
                            ->first();
                        if ($baseUnit) {
                            $baseUnitId = $baseUnit->id;
                        }
                    }

                    $category = \App\Models\UnitCategory::whereRaw("LOWER(name) = ?", [strtolower($name)])
                        ->where('company_id', $companyId)
                        ->withTrashed()
                        ->first();

                    if (!$category) {
                        $category = new \App\Models\UnitCategory();
                        $category->code = $code;
                        $category->name = $name;
                        $category->company_id = $companyId;
                        $category->created_by_user_id = 1;
                        $isNew = true;
                    } else {
                        if ($category->trashed()) {
                            $category->restore();
                        }
                        $category->code = $code;
                        $isNew = false;
                    }

                    if ($baseUnitId) $category->base_unit_id = $baseUnitId;
                    $category->save();

                    $isNew ? $results['created']++ : $results['updated']++;
                } catch (\Throwable $e) {
                    $results['errors'][] = ($item['code'] ?? 'unknown') . ': ' . $e->getMessage();
                    $results['skipped']++;
                }
            }
        });

        return response()->json([
            'code' => 200,
            'message' => 'UOM Category sync completed',
            'data' => $results,
        ]);
    }

    public function syncUomConversions(Request $request)
    {
        $items = $request->json('data');
        if (empty($items) || !is_array($items)) {
            return response()->json(['code' => 400, 'message' => 'data (array of {category_code, uom_code, factor_to_base}) is required'], 400);
        }

        $companyId = $request->input('company_id');
        if (empty($companyId)) {
            return response()->json(['code' => 400, 'message' => 'company_id is required'], 400);
        }

        $results = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];

        \App\Services\WismaService::withoutUomSync(function () use ($items, $companyId, &$results) {
            foreach ($items as $item) {
                try {
                    $categoryCode = $item['category_code'] ?? null;
                    $uomCode = $item['uom_code'] ?? null;
                    $factorToBase = $item['factor_to_base'] ?? null;

                    if (empty($categoryCode) || empty($uomCode)) {
                        $results['errors'][] = 'Missing category_code or uom_code';
                        $results['skipped']++;
                        continue;
                    }

                    $category = \App\Models\UnitCategory::whereRaw("LOWER(name) = ?", [strtolower($categoryCode)])
                        ->where('company_id', $companyId)
                        ->first();

                    if (!$category) {
                        // Try matching by code field
                        $category = \App\Models\UnitCategory::whereRaw("LOWER(code) = ?", [strtolower($categoryCode)])
                            ->where('company_id', $companyId)
                            ->first();
                    }

                    if (!$category) {
                        $results['errors'][] = "Category '{$categoryCode}' not found";
                        $results['skipped']++;
                        continue;
                    }

                    $unit = \App\Models\Unit::whereRaw("LOWER(code) = ?", [strtolower($uomCode)])
                        ->where('company_id', $companyId)
                        ->first();

                    if (!$unit) {
                        $results['errors'][] = "UOM '{$uomCode}' not found";
                        $results['skipped']++;
                        continue;
                    }

                    $unit->unit_category_id = $category->id;
                    if ($factorToBase !== null) {
                        $unit->conversion_factor = $factorToBase;
                    }
                    $unit->save();

                    $results['updated']++;
                } catch (\Throwable $e) {
                    $results['errors'][] = ($item['uom_code'] ?? 'unknown') . ': ' . $e->getMessage();
                    $results['skipped']++;
                }
            }
        });

        return response()->json([
            'code' => 200,
            'message' => 'UOM Conversion sync completed',
            'data' => $results,
        ]);
    }
}
