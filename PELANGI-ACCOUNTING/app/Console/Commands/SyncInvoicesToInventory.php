<?php

namespace App\Console\Commands;

use App\Models\SalesInvoice;
use App\Services\InventorySyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncInvoicesToInventory extends Command
{
    protected $signature = 'inventory:sync-invoices 
                            {--invoice-id= : Sync specific invoice by ID}
                            {--since= : Sync invoices created/updated since date (Y-m-d)}
                            {--force : Force sync even if already synced}
                            {--dry-run : Show what would be synced without actually syncing}';

    protected $description = 'Sync Sales Invoice status to Inventory system';

    public function handle(InventorySyncService $syncService): int
    {
        $specificId = $this->option('invoice-id');
        $since = $this->option('since');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($specificId) {
            return $this->syncSpecificInvoice($syncService, $specificId, $dryRun, $force);
        }

        return $this->syncBatch($syncService, $since, $dryRun, $force);
    }

    private function syncSpecificInvoice(InventorySyncService $syncService, int $id, bool $dryRun, bool $force): int
    {
        $this->info("Syncing specific invoice ID: {$id}");

        $invoice = SalesInvoice::with('salesOrder')->find($id);

        if (!$invoice) {
            $this->error("Invoice ID {$id} not found");
            return 1;
        }

        // Only sync posted invoices
        if ($invoice->status !== 'posted') {
            $this->warn("Invoice {$invoice->invoice_number}: Status is '{$invoice->status}', not 'posted'. Skipping.");
            return 0;
        }

        // Skip if already synced (unless force)
        if (!$force && $invoice->synced_to_inventory_at) {
            $this->warn("Invoice {$invoice->invoice_number}: Already synced at {$invoice->synced_to_inventory_at}");
            $this->warn("Use --force to re-sync");
            return 0;
        }

        return $this->syncInvoice($syncService, $invoice, $dryRun);
    }

    private function syncBatch(InventorySyncService $syncService, ?string $since, bool $dryRun, bool $force): int
    {
        $query = SalesInvoice::with('salesOrder')
            ->where('status', 'posted')  // Only sync posted invoices
            ->whereHas('salesOrder', function ($q) {
                $q->whereNotNull('job_number');
            });

        if ($since) {
            // Sync invoices created/updated since specific date
            $query->where(function ($q) use ($since) {
                $q->where('created_at', '>=', $since . ' 00:00:00')
                  ->orWhere('updated_at', '>=', $since . ' 00:00:00');
            });
        } elseif (!$force) {
            // Default: only sync invoices that haven't been synced yet
            // OR were updated since last sync (for status changes like is_paid)
            $query->where(function ($q) {
                $q->whereNull('synced_to_inventory_at')
                  ->orWhereColumn('updated_at', '>', 'synced_to_inventory_at');
            });
        }

        $invoices = $query->get();

        $this->info("Found {$invoices->count()} invoices to sync");

        if ($invoices->isEmpty()) {
            $this->warn("No invoices to sync");
            return 0;
        }

        $success = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($invoices as $invoice) {
            // Skip non-posted invoices
            if ($invoice->status !== 'posted') {
                $this->line("Invoice {$invoice->invoice_number}: Not posted (status: {$invoice->status}), skipping");
                $skipped++;
                continue;
            }

            // Double check if already synced (unless force)
            if (!$force && $invoice->synced_to_inventory_at && $invoice->updated_at <= $invoice->synced_to_inventory_at) {
                $this->line("Invoice {$invoice->invoice_number}: Already synced, skipping");
                $skipped++;
                continue;
            }

            $result = $this->syncInvoice($syncService, $invoice, $dryRun);
            if ($result === 0) {
                $success++;
            } else {
                $failed++;
            }
        }

        $this->info("Sync completed: {$success} succeeded, {$failed} failed, {$skipped} skipped");

        return $failed > 0 ? 1 : 0;
    }

    private function syncInvoice(InventorySyncService $syncService, SalesInvoice $invoice, bool $dryRun): int
    {
        $salesOrder = $invoice->salesOrder;

        if (!$salesOrder) {
            $this->warn("Invoice {$invoice->invoice_number}: No SalesOrder linked");
            // Mark as failed so we don't keep trying
            $invoice->update(['sync_status' => 'failed']);
            return 1;
        }

        $jobNumber = $salesOrder->job_number;

        if (empty($jobNumber)) {
            $this->warn("Invoice {$invoice->invoice_number}: SalesOrder has no job_number");
            $invoice->update(['sync_status' => 'failed']);
            return 1;
        }

        // Determine status based on invoice state
        $status = $invoice->is_paid ? 'sent' : 'process';

        $invoiceData = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->date?->format('Y-m-d'),
            'amount' => $invoice->total_amount,
            'paid_amount' => $invoice->paid_amount ?? 0,
            'note' => $invoice->is_paid
                ? 'Invoice telah dibayar dari Accounting (scheduled sync)'
                : 'Invoice dibuat di Accounting (scheduled sync)',
            'from_accounting' => true,
        ];

        $this->info("Invoice {$invoice->invoice_number}: {$jobNumber} -> {$status} (posted)");

        if ($dryRun) {
            $this->line("  [DRY RUN] Would sync to Inventory");
            return 0;
        }

        $result = $syncService->notifyInvoiceStatus($jobNumber, $status, $invoiceData);

        if ($result['success']) {
            $this->info("  ✓ Synced successfully");
            
            // Mark invoice as synced
            $invoice->update([
                'synced_to_inventory_at' => now(),
                'sync_status' => 'synced',
            ]);
            
            Log::info('Scheduled sync: Invoice synced to Inventory', [
                'invoice_id' => $invoice->id,
                'job_number' => $jobNumber,
                'status' => $status,
            ]);
            return 0;
        } else {
            $this->error("  ✗ Failed: " . ($result['message'] ?? 'Unknown error'));
            
            // Mark invoice as failed
            $invoice->update(['sync_status' => 'failed']);
            
            Log::error('Scheduled sync: Failed to sync invoice', [
                'invoice_id' => $invoice->id,
                'job_number' => $jobNumber,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
            return 1;
        }
    }
}
