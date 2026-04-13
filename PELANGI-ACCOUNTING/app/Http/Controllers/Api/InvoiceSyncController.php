<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SyncInvoiceToInventoryJob;
use App\Models\InvoiceSyncJob;
use App\Models\SalesInvoice;
use App\Services\InventorySyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceSyncController extends Controller
{
    private $syncService;

    public function __construct(InventorySyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Get list of Sync Jobs with invoice details for monitoring
     * Shows each event (created, deleted) as separate items
     */
    public function index(Request $request)
    {
        $query = InvoiceSyncJob::query();

        if ($request->filled('sync_status')) {
            $syncStatus = $request->input('sync_status');
            if ($syncStatus === 'null') {
                $query->whereNull('status');
            } else {
                $query->where('status', $syncStatus);
            }
        }

        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('job_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $syncJobs = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'code' => 200,
            'data' => $syncJobs->map(function($job) {
                return [
                    'id' => $job->id,
                    'sync_job_id' => $job->id,
                    'event' => $job->event,
                    'sync_status' => $job->status,
                    'sync_created_at' => $job->created_at,
                    'started_at' => $job->started_at,
                    'completed_at' => $job->completed_at,
                    'retry_count' => $job->retry_count,
                    'error_message' => $job->error_message,
                    'execution_time' => $job->getExecutionTime(),
                    'can_retry' => $job->canRetry() || in_array($job->status, ['failed']),
                    'invoice_number' => $job->invoice_number,
                    'job_number' => $job->job_number,
                    'customer_name' => $job->customer_name,
                    'total_amount' => $job->total_amount,
                    'invoice_date' => $job->invoice_date,
                ];
            }),
            'meta' => [
                'current_page' => $syncJobs->currentPage(),
                'total' => $syncJobs->total(),
                'per_page' => $syncJobs->perPage(),
            ]
        ]);
    }

    /**
     * Immediate retry for failed sync (runs immediately, not queued)
     */
    public function retrySync($syncJobId, Request $request)
    {
        $syncJob = InvoiceSyncJob::with(['salesInvoice.salesOrder'])->find($syncJobId);

        if (!$syncJob) {
            return response()->json([
                'code' => 404,
                'message' => 'Sync job not found'
            ], 404);
        }

        $event = $syncJob->event ?? 'created';

        // For deletion events, use snapshot data from sync job record
        if ($event === 'deleted') {
            $jobNumber = $syncJob->job_number;
            $invoiceNumber = $syncJob->invoice_number;

            if (empty($jobNumber) || empty($invoiceNumber)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Missing job_number or invoice_number in sync job for deletion event'
                ], 400);
            }
        } else {
            // For non-deletion events, check sales order or direct job
            $invoice = $syncJob->salesInvoice;
            $salesOrder = $invoice?->salesOrder;

            // Check if sales order has job number, otherwise check if invoice has direct job
            $jobNumber = null;
            if ($salesOrder && !empty($salesOrder->job_number)) {
                $jobNumber = $salesOrder->job_number;
            } elseif ($invoice && $invoice->job) {
                // Use the direct job relationship if sales order doesn't have job number
                $jobNumber = $invoice->job->job_number ?? null;
            }

            if (empty($jobNumber)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Invoice has no Sales Order with job number and no direct job association'
                ], 400);
            }
        }

        try {
            // Update sync job status
            $syncJob->markAsProcessing();

            // Handle deletion event differently - use snapshot data
            if ($event === 'deleted') {
                $invoiceNumber = $syncJob->invoice_number;

                if (empty($jobNumber) || empty($invoiceNumber)) {
                    $errorMessage = 'Missing job_number or invoice_number in sync job for deletion event';
                    $syncJob->markAsFailed($errorMessage);
                    return response()->json([
                        'code' => 400,
                        'message' => $errorMessage,
                    ], 400);
                }

                // Send deletion notification to Inventory
                $result = $this->syncService->notifyInvoiceDeleted($jobNumber, $invoiceNumber);

                if ($result['success']) {
                    // Mark sync job as completed
                    $syncJob->markAsCompleted($result);

                    Log::info('Invoice deletion retry succeeded', [
                        'sync_job_id' => $syncJob->id,
                        'invoice_number' => $invoiceNumber,
                        'job_number' => $jobNumber,
                        'event' => $event,
                    ]);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Deletion sync completed successfully',
                        'data' => [
                            'sync_job_id' => $syncJob->id,
                            'sync_status' => 'completed',
                            'event' => $event,
                        ]
                    ]);
                } else {
                    $errorMessage = $result['message'] ?? 'Unknown error';

                    // Mark sync job as failed
                    $syncJob->markAsFailed($errorMessage, [
                        ['type' => 'error', 'message' => 'Deletion retry failed', 'context' => $result]
                    ]);

                    Log::error('Invoice deletion retry failed', [
                        'sync_job_id' => $syncJob->id,
                        'error' => $errorMessage,
                    ]);

                    return response()->json([
                        'code' => 500,
                        'message' => 'Deletion sync failed: ' . $errorMessage,
                        'data' => [
                            'sync_job_id' => $syncJob->id,
                            'sync_status' => 'failed',
                            'error' => $errorMessage,
                        ]
                    ], 500);
                }
            } else {
                // For created event, sync as posted
                $status = 'process';

                $invoiceData = [
                    'invoice_number' => $invoice->invoice_number,
                    'invoice_date' => $invoice->date?->format('Y-m-d'),
                    'amount' => $invoice->total_amount,
                    'paid_amount' => $invoice->paid_amount ?? 0,
                    'note' => 'Invoice dibuat di Accounting (manual retry)',
                    'from_accounting' => true,
                ];

                // Run sync immediately
                $result = $this->syncService->notifyInvoiceStatus($jobNumber, $status, $invoiceData);

                if ($result['success']) {
                    // Mark sync job as completed
                    $syncJob->markAsCompleted($result);

                    // Update invoice sync status
                    $invoice->update([
                        'sync_status' => 'synced',
                        'synced_to_inventory_at' => now(),
                        'sync_error' => null,
                    ]);

                    Log::info('Invoice sync retry succeeded', [
                        'sync_job_id' => $syncJob->id,
                        'invoice_id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'job_number' => $jobNumber,
                        'event' => $event,
                    ]);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Sync completed successfully',
                        'data' => [
                            'sync_job_id' => $syncJob->id,
                            'sync_status' => 'completed',
                            'event' => $event,
                        ]
                    ]);
                } else {
                    $errorMessage = $result['message'] ?? 'Unknown error';

                    // Mark sync job as failed
                    $syncJob->markAsFailed($errorMessage, [
                        ['type' => 'error', 'message' => 'Retry failed', 'context' => $result]
                    ]);

                    Log::error('Invoice sync retry failed', [
                        'sync_job_id' => $syncJob->id,
                        'invoice_id' => $invoice->id,
                        'error' => $errorMessage,
                    ]);

                    return response()->json([
                        'code' => 500,
                        'message' => 'Sync failed: ' . $errorMessage,
                        'data' => [
                            'sync_job_id' => $syncJob->id,
                            'sync_status' => 'failed',
                            'error' => $errorMessage,
                        ]
                    ], 500);
                }
            }

        } catch (\Exception $e) {
            $syncJob->markAsFailed($e->getMessage(), [
                ['type' => 'exception', 'message' => $e->getMessage(), 'context' => ['trace' => $e->getTraceAsString()]]
            ]);

            Log::error('Invoice sync retry exception', [
                'sync_job_id' => $syncJob->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Queue a retry for failed sync (non-blocking)
     */
    public function queueRetry($syncJobId, Request $request)
    {
        $syncJob = InvoiceSyncJob::find($syncJobId);

        if (!$syncJob) {
            return response()->json([
                'code' => 404,
                'message' => 'Sync job not found'
            ], 404);
        }

        $event = $syncJob->event ?? 'created';

        // Check if we have necessary data
        if ($event === 'deleted' && (empty($syncJob->job_number) || empty($syncJob->invoice_number))) {
            return response()->json([
                'code' => 400,
                'message' => 'Sync job missing required data (job_number or invoice_number)'
            ], 400);
        }

        if ($event !== 'deleted' && !$syncJob->salesInvoice) {
            return response()->json([
                'code' => 400,
                'message' => 'Invoice not found'
            ], 400);
        }

        // Mark for retry
        $syncJob->markForRetry();

        // Dispatch to queue
        if ($event === 'deleted') {
            SyncInvoiceToInventoryJob::dispatchDeletion(
                $syncJob->sales_invoice_id,
                $syncJob->job_number,
                $syncJob->invoice_number,
                $syncJob->id
            );
        } else {
            SyncInvoiceToInventoryJob::dispatch($syncJob->sales_invoice_id, $event, $syncJob->id)
                ->onQueue(env('INVOICE_SYNC_QUEUE', 'default'));
        }

        Log::info('Invoice sync retry queued', [
            'sync_job_id' => $syncJob->id,
            'invoice_id' => $syncJob->sales_invoice_id,
            'event' => $event,
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Sync retry queued successfully',
            'data' => [
                'sync_job_id' => $syncJob->id,
                'sync_status' => 'retrying',
                'event' => $event,
            ]
        ]);
    }

    /**
     * Get sync status for specific Sync Job
     */
    public function status($syncJobId)
    {
        $syncJob = InvoiceSyncJob::find($syncJobId);

        if (!$syncJob) {
            return response()->json([
                'code' => 404,
                'message' => 'Sync job not found'
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => [
                'id' => $syncJob->id,
                'event' => $syncJob->event,
                'status' => $syncJob->status,
                'payload' => $syncJob->payload,
                'result' => $syncJob->result,
                'error_message' => $syncJob->error_message,
                'retry_count' => $syncJob->retry_count,
                'max_retries' => $syncJob->max_retries,
                'started_at' => $syncJob->started_at,
                'completed_at' => $syncJob->completed_at,
                'execution_time' => $syncJob->getExecutionTime(),
                'created_at' => $syncJob->created_at,
                'can_retry' => $syncJob->canRetry() || in_array($syncJob->status, ['failed']),
                'invoice_number' => $syncJob->invoice_number,
                'job_number' => $syncJob->job_number,
                'customer_name' => $syncJob->customer_name,
                'total_amount' => $syncJob->total_amount,
                'invoice_date' => $syncJob->invoice_date?->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Get sync statistics
     */
    public function stats(Request $request)
    {
        $days = $request->input('days', 7);

        // Stats from InvoiceSyncJob
        $jobStats = InvoiceSyncJob::getStats($days);

        // Event breakdown
        $eventStats = [
            'created' => [
                'total' => InvoiceSyncJob::where('event', 'created')->count(),
                'completed' => InvoiceSyncJob::where('event', 'created')->where('status', 'completed')->count(),
                'failed' => InvoiceSyncJob::where('event', 'created')->where('status', 'failed')->count(),
            ],
            'deleted' => [
                'total' => InvoiceSyncJob::where('event', 'deleted')->count(),
                'completed' => InvoiceSyncJob::where('event', 'deleted')->where('status', 'completed')->count(),
                'failed' => InvoiceSyncJob::where('event', 'deleted')->where('status', 'failed')->count(),
            ],
        ];

        return response()->json([
            'code' => 200,
            'data' => [
                'job_stats' => $jobStats,
                'event_stats' => $eventStats,
                'period_days' => $days,
            ]
        ]);
    }

    /**
     * Bulk retry sync for multiple sync jobs
     */
    public function bulkRetry(Request $request)
    {
        $syncJobIds = $request->input('sync_job_ids', []);
        
        if (empty($syncJobIds)) {
            return response()->json([
                'code' => 400,
                'message' => 'No sync job IDs provided'
            ], 400);
        }

        $results = [
            'queued' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($syncJobIds as $syncJobId) {
            try {
                $syncJob = InvoiceSyncJob::find($syncJobId);

                if (!$syncJob) {
                    $results['failed']++;
                    $results['errors'][] = "Sync job {$syncJobId}: Not found";
                    continue;
                }

                $event = $syncJob->event ?? 'created';

                // For deletion events, check snapshot data
                if ($event === 'deleted') {
                    if (empty($syncJob->job_number) || empty($syncJob->invoice_number)) {
                        $results['failed']++;
                        $results['errors'][] = "Sync job {$syncJobId}: Missing snapshot data";
                        continue;
                    }

                    // Mark for retry
                    $syncJob->markForRetry();

                    // Dispatch to queue
                    SyncInvoiceToInventoryJob::dispatchDeletion(
                        $syncJob->sales_invoice_id,
                        $syncJob->job_number,
                        $syncJob->invoice_number,
                        $syncJob->id
                    );

                    $results['queued']++;
                    continue;
                }

                // For non-deletion events, need invoice to exist
                if (!$syncJob->salesInvoice) {
                    $results['failed']++;
                    $results['errors'][] = "Sync job {$syncJobId}: Invoice not found";
                    continue;
                }

                // Mark for retry
                $syncJob->markForRetry();

                // Dispatch to queue
                SyncInvoiceToInventoryJob::dispatch($syncJob->sales_invoice_id, $event, $syncJob->id)
                    ->onQueue(env('INVOICE_SYNC_QUEUE', 'default'));

                $results['queued']++;

            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Sync job {$syncJobId}: " . $e->getMessage();
            }
        }

        return response()->json([
            'code' => 200,
            'message' => "Bulk retry completed: {$results['queued']} queued, {$results['failed']} failed",
            'data' => $results
        ]);
    }

    /**
     * Clear old completed sync jobs
     */
    public function clearData(Request $request)
    {
        $days = $request->input('days', 7);
        
        // Validate days parameter
        if ($days < 1 || $days > 365) {
            return response()->json([
                'code' => 400,
                'message' => 'Days parameter must be between 1 and 365'
            ], 400);
        }

        // Count records that would be deleted
        $deleteBeforeDate = now()->subDays($days);
        $countToDelete = InvoiceSyncJob::where('status', InvoiceSyncJob::STATUS_COMPLETED)
            ->where('completed_at', '<=', $deleteBeforeDate)
            ->count();

        if ($countToDelete === 0) {
            return response()->json([
                'code' => 200,
                'message' => 'No records to delete',
                'data' => [
                    'records_to_delete' => 0,
                    'days_old' => $days
                ]
            ]);
        }

        // Perform the deletion
        $deletedCount = InvoiceSyncJob::where('status', InvoiceSyncJob::STATUS_COMPLETED)
            ->where('completed_at', '<=', $deleteBeforeDate)
            ->delete();

        Log::info('Cleared old invoice sync jobs', [
            'days_old' => $days,
            'records_deleted' => $deletedCount,
            'deleted_before' => $deleteBeforeDate->toISOString()
        ]);

        return response()->json([
            'code' => 200,
            'message' => "Successfully cleared {$deletedCount} old sync jobs completed before {$days} days ago",
            'data' => [
                'records_deleted' => $deletedCount,
                'days_old' => $days
            ]
        ]);
    }
}
