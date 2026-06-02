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
     * Sync approved purchase order to Wisma system
     *
     * @param PurchaseOrder $purchaseOrder
     * @return array
     */
    public function syncApprovedPurchaseOrder(PurchaseOrder $purchaseOrder): array
    {
        $apiUrl = config('services.wisma.url', env('WISMA_API_URL', 'https://wisma-dev.pelangisentralkreasi.co.id/api'));
        $apiToken = config('services.wisma.token', env('WISMA_API_TOKEN', 'prima-accounting-secret-token'));

        $endpoint = rtrim($apiUrl, '/') . '/external/purchase-requests/approve';

        $purchaseOrder->loadMissing('items.product');

        $prNo = $purchaseOrder->reference_no;
        $poNo = $purchaseOrder->purchase_order_no;
        $payload = $this->buildApprovedPurchaseOrderPayload($purchaseOrder);

        if (empty($prNo)) {
            $warnMessage = "Skipping Wisma PO approval sync: Reference No (Purchase Request No) is empty for PO #{$poNo}";
            Log::warning($warnMessage);
            
            $this->notifyUser(
                title: 'Wisma Sync Skipped',
                body: "Reference No is empty. PO #{$poNo} approved in Pelangi but could not be approved in Wisma.",
                type: 'warning',
                persistent: true
            );

            return [
                'success' => false,
                'message' => 'Reference No is empty.'
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
        $apiUrl = config('services.wisma.url', env('WISMA_API_URL', 'https://wisma-dev.pelangisentralkreasi.co.id/api'));
        $apiToken = config('services.wisma.token', env('WISMA_API_TOKEN', 'prima-accounting-secret-token'));

        $endpoint = rtrim($apiUrl, '/') . '/external/purchase-requests/reject';

        $prNo = $purchaseOrder->reference_no;
        $poNo = $purchaseOrder->purchase_order_no;
        $comment = $comment ?: $purchaseOrder->description;

        if (empty($prNo)) {
            $warnMessage = "Skipping Wisma PO rejection sync: Reference No (Purchase Request No) is empty for PO #{$poNo}";
            Log::warning($warnMessage);

            $this->notifyUser(
                title: 'Wisma Sync Skipped',
                body: "Reference No is empty. PO #{$poNo} rejected in Pelangi but could not be rejected in Wisma.",
                type: 'warning',
                persistent: true
            );

            return [
                'success' => false,
                'message' => 'Reference No is empty.'
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

    protected function buildApprovedPurchaseOrderPayload(PurchaseOrder $purchaseOrder): array
    {
        return [
            'purchase_request_no' => $purchaseOrder->reference_no,
            'po_no' => $purchaseOrder->purchase_order_no,
            'comment' => $purchaseOrder->description,
            'items' => $purchaseOrder->items
                ->map(fn ($item) => $this->buildApprovedPurchaseOrderItemPayload($item))
                ->filter()
                ->values()
                ->all(),
        ];
    }

    protected function buildApprovedPurchaseOrderItemPayload(object $item): ?array
    {
        $materialCode = $this->extractMaterialCode($item);
        if ($materialCode === null) {
            return null;
        }

        $payload = [
            'material_code' => $materialCode,
            'unit_price' => $this->normalizeDecimalValue($item->unit_price ?? 0),
        ];

        $qtyOrder = $this->normalizeDecimalValue($item->quantity ?? 0);
        if ($qtyOrder !== 0) {
            $payload['qty_order'] = $qtyOrder;
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

    public function syncUom(Unit $unit, string $action = 'update'): array
    {
        $apiUrl = config('services.wisma.url', env('WISMA_API_URL', 'https://api-dev.wismaatlet.id/api/'));
        $apiToken = config('services.wisma.token', env('WISMA_API_TOKEN', 'prima-accounting-secret-token'));

        $endpoint = rtrim($apiUrl, '/') . '/external/uoms/sync';
        $action = strtolower($action);


        $payload = [
            'code' => $unit->code,
            'action' => $action,
        ];

        if ($action !== 'delete') {
            $payload['name'] = $unit->name;
            $payload['description'] = $unit->description;
            $payload['is_active'] = $unit->trashed() ? 0 : (int) $unit->is_active;
        }

        try {
            Log::info("Sending UOM sync to Wisma", [
                'unit_id' => $unit->id,
                'code' => $unit->code,
                'action' => $action,
                'endpoint' => $endpoint,
                'payload' => $payload,
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
                    'action' => $action,
                ]);

                return [
                    'success' => true,
                    'data' => $responseBody,
                ];
            }

            $errorMessage = $responseBody['message'] ?? 'Unknown error';
            Log::error("Failed to sync UOM to Wisma. Status: {$response->status()}", [
                'unit_id' => $unit->id,
                'code' => $unit->code,
                'action' => $action,
                'payload' => $payload,
                'response' => $responseBody,
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'response' => $responseBody,
            ];
        } catch (\Throwable $e) {
            Log::error("Error syncing UOM to Wisma: " . $e->getMessage(), [
                'exception' => $e,
                'unit_id' => $unit->id,
                'code' => $unit->code,
                'action' => $action,
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
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
