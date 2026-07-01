<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Services\CodeGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOutstandingJournalEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounting:create-outstanding-journals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create journal entries for outstanding receivables and payables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to create journal entries for outstanding amounts...');

        $receivableCount = $this->processReceivables();
        $payableCount = $this->processPayables();

        $this->info("Completed! Created {$receivableCount} receivable journal entries and {$payableCount} payable journal entries.");
        
        return Command::SUCCESS;
    }

    /**
     * Process outstanding receivables
     */
    private function processReceivables(): int
    {
        $count = 0;
        
        $invoices = SalesInvoice::whereNotIn('status', ['cancelled', 'written_off'])
            ->with('company')
            ->get();

        foreach ($invoices as $invoice) {
            try {
                $existingEntry = JournalEntry::where('reference_type', SalesInvoice::class)
                    ->where('reference_id', $invoice->id)
                    ->where('sub_module', 'piutang_outstanding')
                    ->first();

                if ($existingEntry) {
                    if ($invoice->outstanding_amount <= 0) {
                        $existingEntry->items()->delete();
                        $existingEntry->delete();
                        $count++;
                        continue;
                    }
                    
                    if (abs($existingEntry->total_amount - $invoice->outstanding_amount) > 0.01) {
                        $this->updateReceivableJournalEntry($existingEntry, $invoice);
                        $count++;
                    }
                    continue;
                }
                
                if ($invoice->outstanding_amount <= 0) {
                    continue;
                }

                $this->createReceivableJournalEntry($invoice);
                $count++;
                
            } catch (\Exception $e) {
                $this->error("Error processing SalesInvoice ID {$invoice->id}: " . $e->getMessage());
                Log::error("Error creating journal entry for SalesInvoice {$invoice->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return $count;
    }

    /**
     * Process outstanding payables
     */
    private function processPayables(): int
    {
        $count = 0;
        
        $invoices = PurchaseInvoice::whereNotIn('status', ['cancelled', 'written_off'])
            ->with('company')
            ->get();

        foreach ($invoices as $invoice) {
            try {
                $existingEntry = JournalEntry::where('reference_type', PurchaseInvoice::class)
                    ->where('reference_id', $invoice->id)
                    ->where('sub_module', 'utang_outstanding')
                    ->first();

                if ($existingEntry) {
                    if ($invoice->outstanding_amount <= 0) {
                        $existingEntry->items()->delete();
                        $existingEntry->delete();
                        $count++;
                        continue;
                    }
                    
                    if (abs($existingEntry->total_amount - $invoice->outstanding_amount) > 0.01) {
                        $this->updatePayableJournalEntry($existingEntry, $invoice);
                        $count++;
                    }
                    continue;
                }
                
                if ($invoice->outstanding_amount <= 0) {
                    continue;
                }

                $this->createPayableJournalEntry($invoice);
                $count++;
                
            } catch (\Exception $e) {
                $this->error("Error processing PurchaseInvoice ID {$invoice->id}: " . $e->getMessage());
                Log::error("Error creating journal entry for PurchaseInvoice {$invoice->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return $count;
    }

    /**
     * Create journal entry for receivable outstanding
     */
    private function createReceivableJournalEntry(SalesInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $receivableAccount = AccountMapping::getAccountMapping('sales_invoice', 'accounts_receivable', $invoice->company_id);
            if (!$receivableAccount) {
                throw new \Exception('Accounts Receivable account not found for company ' . $invoice->company_id);
            }

            $revenueAccount = AccountMapping::getAccountMapping('sales_invoice', 'sales', $invoice->company_id);
            if (!$revenueAccount) {
                throw new \Exception('Revenue account not found for company ' . $invoice->company_id);
            }

            $outstandingAmount = (float) $invoice->outstanding_amount;
            if ($outstandingAmount <= 0) {
                return;
            }

            $codeService = app(CodeGeneratorService::class);
            $entryNumber = $codeService->generateCode('journal_entry', $invoice->company_id);

            $departmentId = \App\Models\Department::first()?->id ?? 1;
            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $invoice->date,
                'reference_no' => $invoice->invoice_number,
                'description' => __('Receivable - Invoice :number', ['number' => $invoice->invoice_number]),
                'amount' => $outstandingAmount,
                'total_amount' => $outstandingAmount,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'piutang_outstanding',
                'reference_type' => SalesInvoice::class,
                'reference_id' => $invoice->id,
                'cash_bank_transaction_id' => null,
                'department_id' => $departmentId,
                'posted_by_user_id' => 1, // System user
                'posted_at' => now(),
                'company_id' => $invoice->company_id,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $receivableAccount->id,
                'debit' => $outstandingAmount,
                'credit' => 0,
                'notes' => __('Receivable - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $outstandingAmount,
                'notes' => __('Revenue - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);
        });
    }

    /**
     * Update journal entry for receivable outstanding
     */
    private function updateReceivableJournalEntry(JournalEntry $journalEntry, SalesInvoice $invoice): void
    {
        DB::transaction(function () use ($journalEntry, $invoice) {
            $outstandingAmount = (float) $invoice->outstanding_amount;
            
            $journalEntry->update([
                'amount' => $outstandingAmount,
                'total_amount' => $outstandingAmount,
                'date' => $invoice->date,
                'updated_by_user_id' => 1,
            ]);

            $journalEntry->items()->delete();

            $receivableAccount = AccountMapping::getAccountMapping('sales_invoice', 'accounts_receivable', $invoice->company_id);
            $revenueAccount = AccountMapping::getAccountMapping('sales_invoice', 'sales', $invoice->company_id);
            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $receivableAccount->id,
                'debit' => $outstandingAmount,
                'credit' => 0,
                'notes' => __('Receivable - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $outstandingAmount,
                'notes' => __('Revenue - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);
        });
    }

    /**
     * Create journal entry for payable outstanding
     */
    private function createPayableJournalEntry(PurchaseInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $payableAccount = AccountMapping::getAccountMapping('purchase_invoice', 'accounts_payable', $invoice->company_id);
            if (!$payableAccount) {
                throw new \Exception('Accounts Payable account not found for company ' . $invoice->company_id);
            }

            $expenseAccount = AccountMapping::getAccountMapping('purchase_invoice', 'purchases', $invoice->company_id);
            if (!$expenseAccount) {
                throw new \Exception('Expense account not found for company ' . $invoice->company_id);
            }

            $outstandingAmount = (float) $invoice->outstanding_amount;
            if ($outstandingAmount <= 0) {
                return;
            }

            $codeService = app(CodeGeneratorService::class);
            $entryNumber = $codeService->generateCode('journal_entry', $invoice->company_id);

            $departmentId = \App\Models\Department::first()?->id ?? 1;
            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $invoice->date,
                'reference_no' => $invoice->invoice_number,
                'description' => __('Payable - Invoice :number', ['number' => $invoice->invoice_number]),
                'amount' => $outstandingAmount,
                'total_amount' => $outstandingAmount,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'utang_outstanding',
                'reference_type' => PurchaseInvoice::class,
                'reference_id' => $invoice->id,
                'cash_bank_transaction_id' => null,
                'department_id' => $departmentId,
                'posted_by_user_id' => 1, 
                'posted_at' => now(),
                'company_id' => $invoice->company_id,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $expenseAccount->id,
                'debit' => $outstandingAmount,
                'credit' => 0,
                'notes' => __('Expense - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payableAccount->id,
                'debit' => 0,
                'credit' => $outstandingAmount,
                'notes' => __('Payable - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);
        });
    }

    /**
     * Update journal entry for payable outstanding
     */
    private function updatePayableJournalEntry(JournalEntry $journalEntry, PurchaseInvoice $invoice): void
    {
        DB::transaction(function () use ($journalEntry, $invoice) {
            $outstandingAmount = (float) $invoice->outstanding_amount;
            
            $journalEntry->update([
                'amount' => $outstandingAmount,
                'total_amount' => $outstandingAmount,
                'date' => $invoice->date,
                'updated_by_user_id' => 1,
            ]);

            $journalEntry->items()->delete();

            $payableAccount = AccountMapping::getAccountMapping('purchase_invoice', 'accounts_payable', $invoice->company_id);
            $expenseAccount = AccountMapping::getAccountMapping('purchase_invoice', 'purchases', $invoice->company_id);
            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $expenseAccount->id,
                'debit' => $outstandingAmount,
                'credit' => 0,
                'notes' => __('Expense - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payableAccount->id,
                'debit' => 0,
                'credit' => $outstandingAmount,
                'notes' => __('Payable - Invoice :number', ['number' => $invoice->invoice_number]),
                'cost_center_id' => $costCenterId,
            ]);
        });
    }
}
