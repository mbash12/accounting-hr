<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WismaService
{
    protected static bool $suppressUomSync = false;

    /**
     * Get API configuration.
     */
    protected function config(): array
    {
        return [
            'url' => config('services.wisma.url', env('WISMA_API_URL', 'https://wisma-dev.pelangisentralkreasi.co.id/api')),
            'token' => config('services.wisma.token', env('WISMA_API_TOKEN', 'prima-accounting-secret-token')),
        ];
    }

    /**
     * Sync approved purchase order to Wisma system
     *
     * @param PurchaseOrder $purchaseOrder
     * @return array
     */
    public function syncApprovedPurchaseOrder(PurchaseOrder $purchaseOrder, ?string $comment = null): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/purchase-requests/approve';

        $purchaseOrder->loadMissing('items.product', 'items.unit', 'items.unit.unitCategory');

        $prNo = $purchaseOrder->reference_no;
        $poNo = $purchaseOrder->purchase_order_no;
        $payload = $this->buildApprovedPurchaseOrderPayload($purchaseOrder, $comment);

        if (empty($prNo)) {
            $warnMessage = "Skipping Wisma PO approval sync: Reference No (Purchase Request No) is empty for PO #{$poNo}";
            Log::warning($warnMessage);
            
            $this->notifyUser(
                title: 'Wisma Sync Skipped',
                body: "Reference No is empty. PO #{$poNo} approved locally but not synced to Wisma.",
                type: 'warning',
                persistent: true
            );

            return [
                'success' => true,
                'skipped' => true,
                'message' => 'Reference No is empty. Approved locally only.'
            ];
        }

        try {
            Log::info("Sending PO approval sync to Wisma for PO #{$poNo} (PR Ref: {$prNo})", [
                'payload' => $payload,
            ]);

            $response = Http::withHeaders([
                'X-Accounting-Token' => $apiToken,
                'Accept' => 'application/json',
            ])->post($endpoint, $payload);

            $responseBody = $response->json();

            if ($response->successful()) {
                Log::info("Successfully synced PO approval to Wisma for PO #{$poNo}");
                
                $message = $responseBody['message'] ?? 'Purchase Request approved and Purchase Order created successfully in Wisma.';
                $this->notifyUser(
                    title: 'Synced to Wisma',
                    body: "PO #{$poNo}: " . $message,
                    type: 'success'
                );

                return [
                    'success' => true,
                    'data' => $responseBody,
                ];
            }

            $errorMessage = $responseBody['message'] ?? 'Unknown error';
            Log::error("Failed to sync PO approval to Wisma for PO #{$poNo}. Status: {$response->status()}, Response: " . json_encode($responseBody));
            
            $this->notifyUser(
                title: 'Wisma Sync Failed',
                body: "Failed to approve PO #{$poNo} in Wisma: {$errorMessage} (HTTP {$response->status()})",
                type: 'danger',
                persistent: true
            );

            return [
                'success' => false,
                'message' => $errorMessage,
                'response' => $responseBody,
            ];
        } catch (\Throwable $e) {
            Log::error("Error syncing PO approval to Wisma for PO #{$poNo}: " . $e->getMessage(), [
                'exception' => $e
            ]);

            $this->notifyUser(
                title: 'Wisma Sync Connection Error',
                body: "Failed to connect to Wisma API for PO #{$poNo}: " . $e->getMessage(),
                type: 'danger',
                persistent: true
            );

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function syncRejectedPurchaseOrder(PurchaseOrder $purchaseOrder, ?string $comment = null): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/purchase-requests/reject';

        $prNo = $purchaseOrder->reference_no;
        $poNo = $purchaseOrder->purchase_order_no;
        $comment = $comment ?: $purchaseOrder->description;

        if (empty($prNo)) {
            $warnMessage = "Skipping Wisma PO rejection sync: Reference No (Purchase Request No) is empty for PO #{$poNo}";
            Log::warning($warnMessage);

            $this->notifyUser(
                title: 'Wisma Sync Skipped',
                body: "Reference No is empty. PO #{$poNo} rejected locally but not synced to Wisma.",
                type: 'warning',
                persistent: true
            );

            return [
                'success' => true,
                'skipped' => true,
                'message' => 'Reference No is empty. Rejected locally only.'
            ];
        }

        try {
            Log::info("Sending PO rejection sync to Wisma for PO #{$poNo} (PR Ref: {$prNo})");

            $response = Http::withHeaders([
                'X-Accounting-Token' => $apiToken,
                'Accept' => 'application/json',
            ])->post($endpoint, [
                'purchase_request_no' => $prNo,
                'comment' => $comment,
            ]);

            $responseBody = $response->json();

            if ($response->successful()) {
                Log::info("Successfully synced PO rejection to Wisma for PO #{$poNo}");

                $message = $responseBody['message'] ?? 'Purchase Request rejected successfully in Wisma.';
                $this->notifyUser(
                    title: 'Synced to Wisma',
                    body: "PO #{$poNo}: " . $message,
                    type: 'success'
                );

                return [
                    'success' => true,
                    'data' => $responseBody,
                ];
            }

            $errorMessage = $responseBody['message'] ?? 'Unknown error';
            Log::error("Failed to sync PO rejection to Wisma for PO #{$poNo}. Status: {$response->status()}, Response: " . json_encode($responseBody));

            $this->notifyUser(
                title: 'Wisma Sync Failed',
                body: "Failed to reject PO #{$poNo} in Wisma: {$errorMessage} (HTTP {$response->status()})",
                type: 'danger',
                persistent: true
            );

            return [
                'success' => false,
                'message' => $errorMessage,
                'response' => $responseBody,
            ];
        } catch (\Throwable $e) {
            Log::error("Error syncing PO rejection to Wisma for PO #{$poNo}: " . $e->getMessage(), [
                'exception' => $e
            ]);

            $this->notifyUser(
                title: 'Wisma Sync Connection Error',
                body: "Failed to connect to Wisma API for rejected PO #{$poNo}: " . $e->getMessage(),
                type: 'danger',
                persistent: true
            );

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function withoutUomSync(callable $callback)
    {
        $previousState = self::$suppressUomSync;
        self::$suppressUomSync = true;

        try {
            return $callback();
        } finally {
            self::$suppressUomSync = $previousState;
        }
    }

    public function shouldSyncUom(): bool
    {
        return !self::$suppressUomSync;
    }

    protected function buildApprovedPurchaseOrderPayload(PurchaseOrder $purchaseOrder, ?string $comment = null): array
    {
        $payload = [
            'purchase_request_no' => $purchaseOrder->reference_no,
            'po_no' => $purchaseOrder->purchase_order_no,
            'items' => $purchaseOrder->items
                ->map(fn ($item) => $this->buildApprovedPurchaseOrderItemPayload($item))
                ->filter()
                ->values()
                ->all(),
        ];

        if ($comment !== null) {
            $payload['comment'] = $comment;
        }

        return $payload;
    }

    protected function buildApprovedPurchaseOrderItemPayload(object $item): ?array
    {
        $materialCode = $this->extractMaterialCode($item);
        if ($materialCode === null) {
            return null;
        }

        $unit = $item->unit ?? null;
        $conversionFactor = $unit ? (float) ($unit->conversion_factor ?? 1) : 1;
        $quantity = $this->normalizeDecimalValue($item->quantity ?? 0);
        $unitPrice = $this->normalizeDecimalValue($item->unit_price ?? 0);
        $lineTotal = $this->normalizeDecimalValue($quantity * $unitPrice);

        $payload = [
            'material_code' => $materialCode,
            'qty_order' => $quantity,
            'uom_code' => $unit ? $unit->code : 'PCS',
            'factor_to_base' => $conversionFactor,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
        ];

        // Add base UOM code from unit conversion category
        if ($unit && $unit->unitCategory) {
            $payload['base_uom_code'] = $unit->unitCategory->base_uom_code ?? null;
            $payload['conversion_category_code'] = $unit->unitCategory->code ?? null;
        }

        // Add notes from item description
        if (!empty($item->description)) {
            $payload['notes'] = $item->description;
        }

        return $payload;
    }

    protected function extractMaterialCode(object $item): ?string
    {
        $product = $item->product ?? null;

        $candidates = [
            $item->material_code ?? null,
            data_get($item, 'meta.material_code'),
            $product?->material_code,
            $product?->code,
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate)) {
                $candidate = trim($candidate);
                if ($candidate !== '') {
                    return $candidate;
                }
            }
            if (is_numeric($candidate)) {
                return (string) $candidate;
            }
        }

        return null;
    }

    protected function normalizeDecimalValue(mixed $value): int|float
    {
        $normalized = (float) $value;

        if (fmod($normalized, 1.0) === 0.0) {
            return (int) $normalized;
        }

        return round($normalized, 2);
    }


    /**
     * Sync a single UOM to Prima/Wisma.
     * Called from Unit model events (created, updated, deleted).
     */
    public function syncUom(Unit $unit, string $action = 'update'): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/uoms/sync';

        if (in_array(strtolower($action), ['delete', 'deleted'])) {
            return $this->syncUomDeletion($unit, $endpoint, $apiToken);
        }

        // Wrap in data array matching the batch sync format
        $payload = [
            'data' => [
                [
                    'code' => $unit->code,
                    'name' => $unit->name,
                ],
            ],
        ];

        try {
            Log::info("Syncing UOM to Wisma", [
                'unit_id' => $unit->id,
                'code' => $unit->code,
                'endpoint' => $endpoint,
            ]);

            $response = Http::withHeaders([
                'X-Accounting-Token' => $apiToken,
                'Accept' => 'application/json',
            ])->post($endpoint, $payload);

            $responseBody = $response->json();

            if ($response->successful()) {
                Log::info("Successfully synced UOM to Wisma", [
                    'unit_id' => $unit->id,
                    'code' => $unit->code,
                ]);
                return ['success' => true, 'data' => $responseBody];
            }

            Log::error("Failed to sync UOM to Wisma. Status: {$response->status()}", [
                'unit_id' => $unit->id,
                'code' => $unit->code,
                'response' => $responseBody,
            ]);

            return [
                'success' => false,
                'message' => $responseBody['message'] ?? 'Unknown error',
                'response' => $responseBody,
            ];
        } catch (\Throwable $e) {
            Log::error("Error syncing UOM to Wisma: " . $e->getMessage(), [
                'exception' => $e,
                'unit_id' => $unit->id,
                'code' => $unit->code,
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Handle UOM deletion sync (separate DELETE request or exclude from batch).
     */
    protected function syncUomDeletion(Unit $unit, string $endpoint, string $apiToken): array
    {
        // Deletion can be sent with an "action": "delete" flag if the API supports it,
        // or the UOM can simply be omitted in future batch syncs.
        try {
            $response = Http::withHeaders([
                'X-Accounting-Token' => $apiToken,
                'Accept' => 'application/json',
            ])->post($endpoint, [
                'data' => [
                    ['code' => $unit->code, 'action' => 'delete'],
                ],
            ]);

            $responseBody = $response->json();
            if ($response->successful()) {
                return ['success' => true, 'data' => $responseBody];
            }

            return [
                'success' => false,
                'message' => $responseBody['message'] ?? 'Unknown error',
            ];
        } catch (\Throwable $e) {
            Log::error("Error syncing UOM deletion to Wisma: " . $e->getMessage(), [
                'exception' => $e,
                'unit_id' => $unit->id,
                'code' => $unit->code,
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Batch sync multiple UOMs.
     * Use this for bulk operations from the UI.
     */
    public function syncUomsBatch(array $units): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/uoms/sync';

        $payload = [
            'data' => array_map(fn (Unit $unit) => [
                'code' => $unit->code,
                'name' => $unit->name,
            ], $units),
        ];

        if (empty($payload['data'])) {
            return ['success' => false, 'message' => 'No UOMs to sync.'];
        }

        return $this->post($endpoint, $apiToken, $payload);
    }

    /**
     * Sync UOM Conversion Categories.
     */
    public function syncUomConversionCategories(array $categories): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/uom-conversion-categories/sync';

        $payload = [
            'data' => array_map(fn (array $cat) => [
                'code' => $cat['code'],
                'name' => $cat['name'],
                'base_uom_code' => $cat['base_uom_code'],
            ], $categories),
        ];

        if (empty($payload['data'])) {
            return ['success' => false, 'message' => 'No categories to sync.'];
        }

        return $this->post($endpoint, $apiToken, $payload);
    }

    /**
     * Sync UOM Conversions.
     *
     * @param array $conversions Array of conversion data
     * @param string|null $syncMode 'replace' for full replace (omitted items disabled), null for upsert
     */
    public function syncUomConversions(array $conversions, ?string $syncMode = null): array
    {
        ['url' => $apiUrl, 'token' => $apiToken] = $this->config();

        $endpoint = rtrim($apiUrl, '/') . '/external/uom-conversions/sync';

        $payload = [
            'data' => array_map(fn (array $conv) => [
                'category_code' => $conv['category_code'],
                'uom_code' => $conv['uom_code'],
                'factor_to_base' => $conv['factor_to_base'],
            ], $conversions),
        ];

        if ($syncMode !== null) {
            $payload['sync_mode'] = $syncMode;
        }

        if (empty($payload['data'])) {
            return ['success' => false, 'message' => 'No conversions to sync.'];
        }

        return $this->post($endpoint, $apiToken, $payload);
    }

    /**
     * Shared POST helper.
     */
    protected function post(string $endpoint, string $apiToken, array $payload): array
    {
        try {
            Log::info("Sending sync to Wisma/Prima", ['endpoint' => $endpoint, 'count' => count($payload['data'] ?? [])]);

            $response = Http::withHeaders([
                'X-Accounting-Token' => $apiToken,
                'Accept' => 'application/json',
            ])->post($endpoint, $payload);

            $responseBody = $response->json();

            if ($response->successful()) {
                $this->notifyUser(
                    title: 'Synced to Prima',
                    body: $responseBody['message'] ?? 'Sync successful.',
                    type: 'success'
                );
                return ['success' => true, 'data' => $responseBody];
            }

            $errorMessage = $responseBody['message'] ?? 'Unknown error';
            $this->notifyUser(
                title: 'Prima Sync Failed',
                body: "{$errorMessage} (HTTP {$response->status()})",
                type: 'danger',
                persistent: true
            );

            return ['success' => false, 'message' => $errorMessage, 'response' => $responseBody];
        } catch (\Throwable $e) {
            Log::error("Error syncing to Wisma/Prima: " . $e->getMessage(), ['exception' => $e]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send user notification using Filament notification system if available
     *
     * @param string $title
     * @param string $body
     * @param string $type ('success', 'warning', 'danger')
     * @param bool $persistent
     * @return void
     */
    protected function notifyUser(string $title, string $body, string $type = 'success', bool $persistent = false): void
    {
        if (class_exists('\Filament\Notifications\Notification')) {
            try {
                $notification = \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body($body);

                if ($type === 'success') {
                    $notification->success();
                } elseif ($type === 'warning') {
                    $notification->warning();
                } else {
                    $notification->danger();
                }

                if ($persistent) {
                    $notification->persistent();
                }

                $notification->send();
            } catch (\Throwable $e) {
                // Ignore errors if notification fails to render/send (e.g. outside session context)
            }
        }
    }
}
