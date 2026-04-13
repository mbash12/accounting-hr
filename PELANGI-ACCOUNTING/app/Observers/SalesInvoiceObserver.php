<?php

namespace App\Observers;

use App\Models\SalesInvoice;
use App\Models\InvoiceSyncJob;
use App\Jobs\SyncInvoiceToInventoryJob;
use Illuminate\Support\Facades\Log;

class SalesInvoiceObserver
{
    /**
     * Handle the SalesInvoice "created" event.
     * Only sync if invoice status is 'posted'
     */
    public function created(SalesInvoice $salesInvoice): void
    {
        // Only sync posted invoices
        if ($salesInvoice->status !== 'posted') {
            Log::debug('SalesInvoiceObserver: Invoice not posted, skipping sync', [
                'sales_invoice_id' => $salesInvoice->id,
                'status' => $salesInvoice->status,
            ]);
            return;
        }

        $this->dispatchSyncJob($salesInvoice, 'created');
    }

    /**
     * Handle the SalesInvoice "updated" event.
     * Only sync when invoice status changes to 'posted'
     */
    public function updated(SalesInvoice $salesInvoice): void
    {
        // Only sync if status changed
        if (!$salesInvoice->isDirty('status')) {
            return;
        }

        // Sync only when status changes to posted (create invoice in inventory)
        if ($salesInvoice->status === 'posted') {
            $this->dispatchSyncJob($salesInvoice, 'created');
        }
    }

    /**
     * Handle the SalesInvoice "deleted" event.
     * Notify inventory system when an invoice is deleted.
     * Skip sync only for draft invoices.
     */
    public function deleted(SalesInvoice $salesInvoice): void
    {
        // Only skip draft invoices
        if ($salesInvoice->status === 'draft') {
            Log::debug('SalesInvoiceObserver: Draft invoice deleted, skipping sync', [
                'sales_invoice_id' => $salesInvoice->id,
                'status' => $salesInvoice->status,
            ]);
            return;
        }

        // For deletion, pass necessary data directly since model will be gone when job runs
        $salesOrder = $salesInvoice->salesOrder;
        if (!$salesOrder || empty($salesOrder->job_number)) {
            Log::debug('SalesInvoiceObserver: Cannot sync deletion - no SalesOrder or job_number', [
                'sales_invoice_id' => $salesInvoice->id,
            ]);
            return;
        }

        $this->dispatchDeletionSyncJob($salesInvoice, $salesOrder->job_number);
    }

    /**
     * Dispatch sync job to queue (non-blocking)
     */
    private function dispatchSyncJob(SalesInvoice $salesInvoice, string $event): void
    {
        Log::info('SalesInvoiceObserver: Dispatching sync job', [
            'sales_invoice_id' => $salesInvoice->id,
            'event' => $event,
        ]);

        // Create InvoiceSyncJob record with snapshot data for monitoring
        $syncJob = InvoiceSyncJob::create([
            'sync_type' => 'invoice_to_inventory',
            'status' => InvoiceSyncJob::STATUS_PENDING,
            'sales_invoice_id' => $salesInvoice->id,
            'company_id' => $salesInvoice->company_id,
            'event' => $event,
            'max_retries' => 3,
            // Snapshot essential data
            'invoice_number' => $salesInvoice->invoice_number,
            'job_number' => $salesInvoice->salesOrder?->job_number,
            'customer_name' => $salesInvoice->customer?->name,
            'total_amount' => $salesInvoice->total_amount,
            'invoice_date' => $salesInvoice->date,
        ]);

        // Dispatch job with sync job ID
        SyncInvoiceToInventoryJob::dispatch($salesInvoice->id, $event, $syncJob->id);
    }

    /**
     * Dispatch deletion sync job with necessary data
     * (job number and invoice number since model will be deleted when job runs)
     */
    private function dispatchDeletionSyncJob(SalesInvoice $salesInvoice, string $jobNumber): void
    {
        Log::info('SalesInvoiceObserver: Dispatching deletion sync job', [
            'sales_invoice_id' => $salesInvoice->id,
            'job_number' => $jobNumber,
            'invoice_number' => $salesInvoice->invoice_number,
        ]);

        // Create InvoiceSyncJob record with snapshot data for monitoring
        $syncJob = InvoiceSyncJob::create([
            'sync_type' => 'invoice_to_inventory',
            'status' => InvoiceSyncJob::STATUS_PENDING,
            'sales_invoice_id' => $salesInvoice->id,
            'company_id' => $salesInvoice->company_id,
            'event' => 'deleted',
            'max_retries' => 3,
            // Snapshot essential data
            'invoice_number' => $salesInvoice->invoice_number,
            'job_number' => $jobNumber,
            'customer_name' => $salesInvoice->customer?->name,
            'total_amount' => $salesInvoice->total_amount,
            'invoice_date' => $salesInvoice->date,
        ]);

        // Dispatch deletion job with necessary data
        SyncInvoiceToInventoryJob::dispatchDeletion(
            $salesInvoice->id,
            $jobNumber,
            $salesInvoice->invoice_number,
            $syncJob->id
        );
    }
}
