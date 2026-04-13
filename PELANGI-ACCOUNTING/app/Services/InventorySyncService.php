<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InventorySyncService
{
    private string $inventoryApiUrl;
    private string $bearerToken;

    public function __construct()
    {
        $this->inventoryApiUrl = rtrim(config('app.inventory_api_url', env('INVENTORY_API_URL', 'http://localhost:8000/api')), '/');
        $this->bearerToken = env('INVENTORY_SYNC_TOKEN', '');
    }

    /**
     * Notify Inventory system about invoice status change
     * 
     * @param string $jobNumber The job number from Sales Order
     * @param string $status 'process' (invoice created) or 'sent' (invoice paid)
     * @param array $invoiceData Additional invoice data
     * @return array
     */
    public function notifyInvoiceStatus(string $jobNumber, string $status, array $invoiceData = []): array
    {
        if (empty($jobNumber)) {
            Log::debug('InventorySyncService: No job_number provided, skipping notification');
            return ['success' => false, 'message' => 'No job_number provided'];
        }

        $url = $this->inventoryApiUrl . '/invoice-status/sync';

        $payload = array_merge([
            'job_number' => $jobNumber,
            'status' => $status,
        ], $invoiceData);

        Log::info('Notifying Inventory about invoice status', [
            'url' => $url,
            'job_number' => $jobNumber,
            'status' => $status,
        ]);

        try {
            $response = Http::withHeaders([
                'X-Internal-Api-Key' => $this->bearerToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                Log::info('Inventory notified successfully', [
                    'job_number' => $jobNumber,
                    'status' => $status,
                    'payload' => $payload,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $errorMsg = 'Inventory API error: ' . $response->status() . ' - ' . $response->body();
                Log::error($errorMsg, [
                    'job_number' => $jobNumber,
                    'payload' => $payload
                ]);
                return [
                    'success' => false,
                    'message' => $errorMsg
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify Inventory', [
                'error' => $e->getMessage(),
                'job_number' => $jobNumber,
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Notify when Sales Invoice is created (set status to 'process')
     */
    public function notifyInvoiceCreated(string $jobNumber, array $invoiceData): array
    {
        return $this->notifyInvoiceStatus($jobNumber, 'process', $invoiceData);
    }

    /**
     * Notify when Sales Invoice is paid (set status to 'sent')
     */
    public function notifyInvoicePaid(string $jobNumber, array $invoiceData): array
    {
        return $this->notifyInvoiceStatus($jobNumber, 'sent', $invoiceData);
    }

    /**
     * Notify when Sales Invoice is deleted
     */
    public function notifyInvoiceDeleted(string $jobNumber, string $invoiceNumber): array
    {
        $url = $this->inventoryApiUrl . '/invoice-status/delete';

        $payload = [
            'job_number' => $jobNumber,
            'invoice_number' => $invoiceNumber,
        ];

        Log::info('Notifying Inventory about invoice deletion', [
            'url' => $url,
            'job_number' => $jobNumber,
            'invoice_number' => $invoiceNumber,
        ]);

        try {
            $response = Http::withHeaders([
                'X-Internal-Api-Key' => $this->bearerToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                Log::info('Inventory notified successfully about invoice deletion', [
                    'job_number' => $jobNumber,
                    'invoice_number' => $invoiceNumber,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $errorMsg = 'Inventory API error: ' . $response->status() . ' - ' . $response->body();
                Log::error($errorMsg, [
                    'job_number' => $jobNumber,
                    'invoice_number' => $invoiceNumber,
                    'payload' => $payload
                ]);
                return [
                    'success' => false,
                    'message' => $errorMsg
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify Inventory about invoice deletion', [
                'error' => $e->getMessage(),
                'job_number' => $jobNumber,
                'invoice_number' => $invoiceNumber,
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
