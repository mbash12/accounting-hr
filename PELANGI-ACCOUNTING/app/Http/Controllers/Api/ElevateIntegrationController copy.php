<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ElevateIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ElevateIntegrationController extends Controller
{
    public function __construct(
        protected ElevateIntegrationService $integrationService
    ) {}

    /**
     * POST /api/integration/work-orders
     *
     * Receive a completed Work Order from Elevate and automatically:
     *  1. Find or create the Customer (Contact)
     *  2. Create a Sales Invoice
     *  3. Create a Receivable Payment (allocating the full invoice amount)
     *  4. Execute journal postings (via existing services)
     *
     * Fully idempotent: calling with the same work_order_id multiple times
     * returns the same result without creating duplicate records.
     *
     * ── Skenario A: WO dengan rincian material + jasa ──
     * {
     *   "work_order_id":     "12345",              // required
     *   "work_order_number": "WO-202607001",        // required
     *   "company_id":        1,                    // required
     *   "billing_amount":    1500000,               // optional — total WO sebagai referensi
     *   "customer":          { ... },              // required
     *   "items": [                                 // required jika billing_amount tidak ada
     *     {
     *       "product_code": "SVC-001",             // optional (pre-synced)
     *       "description":  "Jasa Pemasangan",
     *       "quantity":     1,
     *       "unit_price":   500000,
     *       "unit_code":    "JOB"                  // optional
     *     }
     *   ],
     *   "bank_account_id":  3,                     // optional
     *   "payment_date":     "2026-07-01",           // optional (defaults to today)
     *   "invoice_date":     "2026-07-01"            // optional (defaults to today)
     * }
     *
     * ── Skenario B: WO jasa only (tanpa rincian item) ──
     * {
     *   "work_order_id":     "12345",              // required
     *   "work_order_number": "WO-202607001",        // required
     *   "company_id":        1,                    // required
     *   "billing_amount":    1500000,               // WAJIB jika items kosong
     *   "customer":          { ... },              // required
     *   // items tidak perlu dikirim
     *   "bank_account_id":  3,                     // optional
     *   "payment_date":     "2026-07-01"            // optional
     * }
     */
    public function processWorkOrder(Request $request): JsonResponse
    {
        // ── Input validation ──────────────────────────────────────────────────
        try {
            $validated = $request->validate([
                'work_order_id'          => ['required', 'string'],
                'work_order_number'      => ['required', 'string'],
                'company_id'             => ['required', 'integer', 'exists:companies,id'],
                'billing_amount'         => ['nullable', 'numeric', 'min:0'],
                'customer'               => ['required', 'array'],
                'customer.name'          => ['required', 'string', 'max:255'],
                'customer.email'         => ['required', 'email', 'max:255'],
                'customer.phone'         => ['nullable', 'string', 'max:50'],
                // items opsional — jika kosong, billing_amount wajib ada (WO jasa only)
                'items'                  => ['nullable', 'array'],
                'items.*.product_code'   => ['nullable', 'string'],
                'items.*.description'    => ['nullable', 'string'],
                'items.*.quantity'       => ['required_with:items.*', 'numeric', 'min:0.0001'],
                'items.*.unit_price'     => ['required_with:items.*', 'numeric', 'min:0'],
                'items.*.unit_code'      => ['nullable', 'string'],
                'bank_account_id'        => ['nullable', 'integer'],
                'payment_date'           => ['nullable', 'date'],
                'invoice_date'           => ['nullable', 'date'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code'    => 422,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }

        // ── Aturan bisnis: harus ada items ATAU billing_amount ────────────────
        $hasItems         = !empty($validated['items']);
        $hasBillingAmount = isset($validated['billing_amount']) && $validated['billing_amount'] > 0;

        if (!$hasItems && !$hasBillingAmount) {
            return response()->json([
                'code'    => 422,
                'message' => 'Validation failed.',
                'errors'  => [
                    'items' => ['Harus ada minimal 1 item, atau isi billing_amount untuk Work Order jasa only.'],
                ],
            ], 422);
        }

        // ── Delegate to service ───────────────────────────────────────────────
        try {
            $result = $this->integrationService->processWorkOrder($validated);

            $statusCode = $result['action'] === 'already_processed' ? 200 : 201;

            return response()->json([
                'code'    => $statusCode,
                'message' => $result['action'] === 'already_processed'
                    ? 'Work Order already processed. Returning existing records.'
                    : 'Work Order processed successfully.',
                'data'    => $result,
            ], $statusCode);

        } catch (\InvalidArgumentException $e) {
            // Business rule violations (missing product, no bank account, etc.)
            Log::warning('[Elevate] Business rule violation', [
                'work_order_id' => $validated['work_order_id'] ?? null,
                'error'         => $e->getMessage(),
            ]);

            return response()->json([
                'code'    => 422,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('[Elevate] Unexpected error processing Work Order', [
                'work_order_id' => $validated['work_order_id'] ?? null,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code'    => 500,
                'message' => 'An unexpected error occurred while processing the Work Order.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
