<?php

namespace App\Jobs;

use App\Models\SalesInvoice;
use App\Models\InvoiceSyncJob;
use App\Services\InventorySyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncInvoiceToInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // Retry after 30s, 60s, 120s

    private int $salesInvoiceId;
    private string $event;
    private ?int $syncJobId;

    // For deletion events - data stored before model is deleted
    private ?string $jobNumber = null;
    private ?string $invoiceNumber = null;

    public function __construct(int $salesInvoiceId, string $event, ?int $syncJobId = null, ?string $jobNumber = null, ?string $invoiceNumber = null)
    {
        $this->salesInvoiceId = $salesInvoiceId;
        $this->event = $event;
        $this->syncJobId = $syncJobId;
        $this->jobNumber = $jobNumber;
        $this->invoiceNumber = $invoiceNumber;
        // Use default queue or configure via env
        $this->onQueue(env('INVOICE_SYNC_QUEUE', 'default'));
    }

    /**
     * Dispatch a deletion sync job with necessary data
     */
    public static function dispatchDeletion(int $salesInvoiceId, string $jobNumber, string $invoiceNumber, ?int $syncJobId = null): void
    {
        $job = new static($salesInvoiceId, 'deleted', $syncJobId, $jobNumber, $invoiceNumber);
        $job->onQueue(env('INVOICE_SYNC_QUEUE', 'default'));
        dispatch($job);
    }

    public function handle(InventorySyncService $syncService): void
    {
        // Handle deletion event - use pre-fetched data since invoice is deleted
        if ($this->event === 'deleted') {
            $this->handleDeletionWithData($syncService);
            return;
        }

        $salesInvoice = SalesInvoice::with('salesOrder')->find($this->salesInvoiceId);

        if (!$salesInvoice) {
            Log::warning('SyncInvoiceToInventoryJob: SalesInvoice not found', [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);
            return;
        }

        // Create or get sync job record
        $syncJob = $this->getOrCreateSyncJob($salesInvoice);
        $syncJob->markAsProcessing();

        // Only sync posted invoices for other events
        if ($salesInvoice->status !== 'posted') {
            $message = "Invoice not posted, skipping sync. Status: {$salesInvoice->status}";
            Log::debug('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);

            $syncJob->markAsFailed($message);
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $message);
            return;
        }

        $salesOrder = $salesInvoice->salesOrder;

        if (!$salesOrder) {
            $message = 'No SalesOrder found for this invoice';
            Log::debug('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);
            
            $syncJob->markAsFailed($message);
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $message);
            return;
        }

        $jobNumber = $salesOrder->job_number;

        if (empty($jobNumber)) {
            $message = 'SalesOrder has no job_number';
            Log::debug('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);
            
            $syncJob->markAsFailed($message);
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $message);
            return;
        }

        // Prepare invoice data
        $invoiceData = [
            'invoice_number' => $salesInvoice->invoice_number,
            'invoice_date' => $salesInvoice->date?->format('Y-m-d'),
            'amount' => $salesInvoice->total_amount,
            'paid_amount' => $salesInvoice->paid_amount ?? 0,
            'note' => 'Invoice dibuat di Accounting',
            'from_accounting' => true,  // Flag to prevent circular sync
        ];

        // Set status to sent
        $status = 'sent';

        Log::info('SyncInvoiceToInventoryJob: Sending sync to inventory', [
            'sales_invoice_id' => $this->salesInvoiceId,
            'job_number' => $jobNumber,
            'event' => $this->event,
            'status' => $status,
        ]);

        // Update sync job payload
        $syncJob->update([
            'payload' => [
                'job_number' => $jobNumber,
                'status' => $status,
                'invoice_data' => $invoiceData,
            ],
        ]);

        try {
            // Send notification to Inventory
            $result = $syncService->notifyInvoiceStatus($jobNumber, $status, $invoiceData);

            if ($result['success']) {
                // Mark invoice as synced
                $this->updateInvoiceSyncStatus($salesInvoice, 'synced');
                
                // Mark sync job as completed
                $syncJob->markAsCompleted($result);
                
                Log::info('SyncInvoiceToInventoryJob: Inventory synced successfully', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $jobNumber,
                    'event' => $this->event,
                    'status' => $status,
                ]);
            } else {
                $errorMessage = $result['message'] ?? 'Unknown error';
                
                // Mark invoice as failed
                $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $errorMessage);
                
                // Mark sync job as failed
                $syncJob->markAsFailed($errorMessage, [
                    ['type' => 'error', 'message' => 'Sync failed', 'context' => $result]
                ]);
                
                Log::error('SyncInvoiceToInventoryJob: Failed to sync to Inventory', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $jobNumber,
                    'event' => $this->event,
                    'error' => $errorMessage,
                ]);

                // Throw exception to trigger retry
                throw new \Exception('Failed to sync: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            // Mark invoice as failed
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $e->getMessage());
            
            // Mark sync job as failed
            $syncJob->markAsFailed($e->getMessage(), [
                ['type' => 'exception', 'message' => $e->getMessage(), 'context' => ['trace' => $e->getTraceAsString()]]
            ]);
            
            Log::error('SyncInvoiceToInventoryJob: Exception during sync', [
                'sales_invoice_id' => $this->salesInvoiceId,
                'job_number' => $jobNumber,
                'event' => $this->event,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle invoice deletion event
     */
    private function handleDeletion(SalesInvoice $salesInvoice, InvoiceSyncJob $syncJob, InventorySyncService $syncService): void
    {
        $salesOrder = $salesInvoice->salesOrder;

        if (!$salesOrder) {
            $message = 'No SalesOrder found for this invoice';
            Log::debug('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);

            $syncJob->markAsFailed($message);
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $message);
            return;
        }

        $jobNumber = $salesOrder->job_number;

        if (empty($jobNumber)) {
            $message = 'SalesOrder has no job_number';
            Log::debug('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);

            $syncJob->markAsFailed($message);
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $message);
            return;
        }

        Log::info('SyncInvoiceToInventoryJob: Sending deletion sync to inventory', [
            'sales_invoice_id' => $this->salesInvoiceId,
            'job_number' => $jobNumber,
            'invoice_number' => $salesInvoice->invoice_number,
            'event' => $this->event,
        ]);

        // Update sync job payload
        $syncJob->update([
            'payload' => [
                'job_number' => $jobNumber,
                'invoice_number' => $salesInvoice->invoice_number,
            ],
        ]);

        try {
            // Send deletion notification to Inventory
            $result = $syncService->notifyInvoiceDeleted($jobNumber, $salesInvoice->invoice_number);

            if ($result['success']) {
                // Mark sync job as completed
                $syncJob->markAsCompleted($result);

                Log::info('SyncInvoiceToInventoryJob: Inventory deletion synced successfully', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $jobNumber,
                    'invoice_number' => $salesInvoice->invoice_number,
                ]);
            } else {
                $errorMessage = $result['message'] ?? 'Unknown error';

                // Mark sync job as failed
                $syncJob->markAsFailed($errorMessage, [
                    ['type' => 'error', 'message' => 'Deletion sync failed', 'context' => $result]
                ]);

                Log::error('SyncInvoiceToInventoryJob: Failed to sync deletion to Inventory', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $jobNumber,
                    'error' => $errorMessage,
                ]);

                // Throw exception to trigger retry
                throw new \Exception('Failed to sync deletion: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            // Mark sync job as failed
            $syncJob->markAsFailed($e->getMessage(), [
                ['type' => 'exception', 'message' => $e->getMessage(), 'context' => ['trace' => $e->getTraceAsString()]]
            ]);

            Log::error('SyncInvoiceToInventoryJob: Exception during deletion sync', [
                'sales_invoice_id' => $this->salesInvoiceId,
                'job_number' => $jobNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle invoice deletion event with pre-fetched data
     * This is used when the invoice has already been deleted
     */
    private function handleDeletionWithData(InventorySyncService $syncService): void
    {
        // Get or create sync job record
        $syncJob = $this->getOrCreateSyncJobForDeletion();
        $syncJob->markAsProcessing();

        if (empty($this->jobNumber) || empty($this->invoiceNumber)) {
            $message = 'Missing required data (job_number or invoice_number)';
            Log::warning('SyncInvoiceToInventoryJob: ' . $message, [
                'sales_invoice_id' => $this->salesInvoiceId,
            ]);

            $syncJob->markAsFailed($message);
            return;
        }

        Log::info('SyncInvoiceToInventoryJob: Sending deletion sync to inventory', [
            'sales_invoice_id' => $this->salesInvoiceId,
            'job_number' => $this->jobNumber,
            'invoice_number' => $this->invoiceNumber,
            'event' => $this->event,
        ]);

        // Update sync job payload
        $syncJob->update([
            'payload' => [
                'job_number' => $this->jobNumber,
                'invoice_number' => $this->invoiceNumber,
            ],
        ]);

        try {
            // Send deletion notification to Inventory
            $result = $syncService->notifyInvoiceDeleted($this->jobNumber, $this->invoiceNumber);

            if ($result['success']) {
                // Mark sync job as completed
                $syncJob->markAsCompleted($result);

                Log::info('SyncInvoiceToInventoryJob: Inventory deletion synced successfully', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $this->jobNumber,
                    'invoice_number' => $this->invoiceNumber,
                ]);
            } else {
                $errorMessage = $result['message'] ?? 'Unknown error';

                // Mark sync job as failed
                $syncJob->markAsFailed($errorMessage, [
                    ['type' => 'error', 'message' => 'Deletion sync failed', 'context' => $result]
                ]);

                Log::error('SyncInvoiceToInventoryJob: Failed to sync deletion to Inventory', [
                    'sales_invoice_id' => $this->salesInvoiceId,
                    'job_number' => $this->jobNumber,
                    'error' => $errorMessage,
                ]);

                // Throw exception to trigger retry
                throw new \Exception('Failed to sync deletion: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            // Mark sync job as failed
            $syncJob->markAsFailed($e->getMessage(), [
                ['type' => 'exception', 'message' => $e->getMessage(), 'context' => ['trace' => $e->getTraceAsString()]]
            ]);

            Log::error('SyncInvoiceToInventoryJob: Exception during deletion sync', [
                'sales_invoice_id' => $this->salesInvoiceId,
                'job_number' => $this->jobNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $salesInvoice = SalesInvoice::find($this->salesInvoiceId);
        
        if ($salesInvoice) {
            $this->updateInvoiceSyncStatus($salesInvoice, 'failed', $exception->getMessage());
        }

        // Update sync job if exists
        if ($this->syncJobId) {
            $syncJob = InvoiceSyncJob::find($this->syncJobId);
            if ($syncJob) {
                $syncJob->markAsFailed($exception->getMessage(), [
                    ['type' => 'failed', 'message' => 'Job permanently failed', 'context' => ['error' => $exception->getMessage()]]
                ]);
            }
        }

        Log::error('SyncInvoiceToInventoryJob: Permanently failed', [
            'sales_invoice_id' => $this->salesInvoiceId,
            'event' => $this->event,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Get or create sync job record
     */
    private function getOrCreateSyncJob(SalesInvoice $salesInvoice): InvoiceSyncJob
    {
        if ($this->syncJobId) {
            $syncJob = InvoiceSyncJob::find($this->syncJobId);
            if ($syncJob) {
                return $syncJob;
            }
        }

        return InvoiceSyncJob::create([
            'sync_type' => 'invoice_to_inventory',
            'status' => InvoiceSyncJob::STATUS_PENDING,
            'sales_invoice_id' => $salesInvoice->id,
            'company_id' => $salesInvoice->company_id,
            'event' => $this->event,
            'max_retries' => $this->tries,
        ]);
    }

    /**
     * Get or create sync job record for deletion (when invoice is already deleted)
     */
    private function getOrCreateSyncJobForDeletion(): InvoiceSyncJob
    {
        if ($this->syncJobId) {
            $syncJob = InvoiceSyncJob::find($this->syncJobId);
            if ($syncJob) {
                return $syncJob;
            }
        }

        // Fallback - create without company_id since we don't have the invoice
        return InvoiceSyncJob::create([
            'sync_type' => 'invoice_to_inventory',
            'status' => InvoiceSyncJob::STATUS_PENDING,
            'sales_invoice_id' => $this->salesInvoiceId,
            'event' => $this->event,
            'max_retries' => $this->tries,
        ]);
    }

    /**
     * Update invoice sync status
     */
    private function updateInvoiceSyncStatus(SalesInvoice $invoice, string $status, ?string $error = null): void
    {
        $data = [
            'sync_status' => $status,
            'last_sync_attempt_at' => now(),
        ];

        if ($status === 'synced') {
            $data['synced_to_inventory_at'] = now();
            $data['sync_error'] = null;
            $data['sync_retry_count'] = 0;
        } else {
            $data['sync_error'] = $error;
            if ($status === 'failed') {
                $data['sync_retry_count'] = $invoice->sync_retry_count + 1;
            }
        }

        $invoice->update($data);
    }
}
