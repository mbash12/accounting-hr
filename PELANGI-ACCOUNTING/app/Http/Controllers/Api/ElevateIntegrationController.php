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


    public function processWorkOrder(Request $request): JsonResponse
    {
        $input = $request->all();

        if (isset($input['items']) && is_array($input['items'])) {
            foreach ($input['items'] as &$item) {
                if (isset($item['unit_code']) && is_array($item['unit_code'])) {
                    $extractedCode = null;
                    foreach ($item['unit_code'] as $key => $val) {
                        if (is_array($val) && isset($val['code'])) {
                            $extractedCode = $val['code'];
                            break;
                        }
                    }
                    $item['unit_code'] = $extractedCode;
                }
            }
            $request->replace($input);
        }

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
