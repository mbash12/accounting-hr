<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\ReceivablePayment;
use App\Models\PayablePayment;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReceivablePayableService
{
   
    public function createJournalEntryForReceivablePayment(ReceivablePayment $payment, ?int $departmentId = null, ?int $costCenterId = null): JournalEntry
    {
        return DB::transaction(function () use ($payment, $departmentId, $costCenterId) {
            $existingEntry = JournalEntry::where('reference_type', ReceivablePayment::class)
                ->where('reference_id', $payment->id)
                ->first();

            $departmentId = $departmentId ?? \App\Models\Department::first()?->id ?? 1;
            $costCenterId = $costCenterId ?? \App\Models\CostCenter::first()?->id ?? 1;

            $cashBankAccountId = $payment->bank_account_id;
            if (!$cashBankAccountId) {
                throw new InvalidArgumentException('Bank account is required for receivable payment');
            }

            $receivableAccount = $this->findReceivableAccount($payment->company_id);
            if (!$receivableAccount) {
                throw new InvalidArgumentException('Accounts Receivable account not found. Please create an account with code starting with 11% or name containing "Piutang Usaha"');
            }

            $totalPayment = (float) $payment->total_payment;
            $otherCosts = (float) ($payment->other_costs ?? 0);
            
            $totalDiscount = (float) $payment->items()->sum('discount_amount');
            $totalWriteOff = (float) $payment->items()->sum('write_off_amount');
            $totalSetPayment = (float) $payment->items()->sum('set_payment');
            
            $receivableCreditAmount = $totalSetPayment + $totalDiscount + $totalWriteOff;

            if ($existingEntry) {
                $existingEntry->update([
                    'date' => $payment->payment_date,
                    'reference_no' => $payment->reference_no ?? $payment->payment_number,
                    'description' => $payment->description ?? __('Receivable Payment :number', ['number' => $payment->payment_number]),
                    'amount' => $totalPayment,
                    'total_amount' => $totalPayment,
                    'updated_by_user_id' => Auth::id(),
                ]);

                $existingEntry->items()->delete();

                // Cash/Bank Account - Debit total payment
                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $cashBankAccountId,
                    'debit' => $totalPayment,
                    'credit' => 0,
                    'notes' => __('Cash/Bank - Payment from :customer', ['customer' => $payment->customer->name ?? '']),
                    'cost_center_id' => $costCenterId,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $receivableAccount->id,
                    'debit' => 0,
                    'credit' => $receivableCreditAmount,
                    'notes' => __('Receivable - Payment :number', ['number' => $payment->payment_number]),
                    'cost_center_id' => $costCenterId,
                ]);

                if ($otherCosts > 0 && $payment->other_costs_account_id) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $existingEntry->id,
                        'account_id' => $payment->other_costs_account_id,
                        'debit' => 0,
                        'credit' => $otherCosts,
                        'notes' => __('Other Costs - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }

                if ($totalDiscount > 0) {
                    $discountAccount = \App\Models\AccountMapping::getAccountMapping('receivable_payment', 'discount', $payment->company_id);
                    if ($discountAccount) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $existingEntry->id,
                            'account_id' => $discountAccount->id,
                            'debit' => $totalDiscount,
                            'credit' => 0,
                            'notes' => __('Discount - Payment :number', ['number' => $payment->payment_number]),
                            'cost_center_id' => $costCenterId,
                        ]);
                    }
                }

                if ($totalWriteOff > 0) {
                    $writeOffAccount = \App\Models\AccountMapping::getAccountMapping('receivable_payment', 'write_off', $payment->company_id);
                    if ($writeOffAccount) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $existingEntry->id,
                            'account_id' => $writeOffAccount->id,
                            'debit' => $totalWriteOff,
                            'credit' => 0,
                            'notes' => __('Write Off - Payment :number', ['number' => $payment->payment_number]),
                            'cost_center_id' => $costCenterId,
                        ]);
                    }
                }

                return $existingEntry;
            }

            $codeService = app(CodeGeneratorService::class);
            $entryNumber = $codeService->generateCode('journal_entry', $payment->company_id);

            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $payment->payment_date,
                'reference_no' => $payment->reference_no ?? $payment->payment_number,
                'description' => $payment->description ?? __('Receivable Payment :number', ['number' => $payment->payment_number]),
                'amount' => $totalPayment,
                'total_amount' => $totalPayment,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'pembayaran_piutang',
                'reference_type' => ReceivablePayment::class,
                'reference_id' => $payment->id,
                'cash_bank_transaction_id' => null,
                'department_id' => $departmentId,
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'company_id' => $payment->company_id,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            // Cash/Bank Account - Debit total payment
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $cashBankAccountId,
                'debit' => $totalPayment,
                'credit' => 0,
                'notes' => __('Cash/Bank - Payment from :customer', ['customer' => $payment->customer->name ?? '']),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $receivableAccount->id,
                'debit' => 0,
                'credit' => $receivableCreditAmount,
                'notes' => __('Receivable - Payment :number', ['number' => $payment->payment_number]),
                'cost_center_id' => $costCenterId,
            ]);

            if ($otherCosts > 0 && $payment->other_costs_account_id) {
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $payment->other_costs_account_id,
                    'debit' => 0,
                    'credit' => $otherCosts,
                    'notes' => __('Other Costs - Payment :number', ['number' => $payment->payment_number]),
                    'cost_center_id' => $costCenterId,
                ]);
            }

            if ($totalDiscount > 0) {
                $discountAccount = \App\Models\AccountMapping::getAccountMapping('receivable_payment', 'discount', $payment->company_id);
                if ($discountAccount) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $discountAccount->id,
                        'debit' => $totalDiscount,
                        'credit' => 0,
                        'notes' => __('Discount - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }
            }

            if ($totalWriteOff > 0) {
                $writeOffAccount = \App\Models\AccountMapping::getAccountMapping('receivable_payment', 'write_off', $payment->company_id);
                if ($writeOffAccount) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $writeOffAccount->id,
                        'debit' => $totalWriteOff,
                        'credit' => 0,
                        'notes' => __('Write Off - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }
            }

            return $journalEntry;
        });
    }

    
    public function createJournalEntryForPayablePayment(PayablePayment $payment, ?int $departmentId = null, ?int $costCenterId = null): JournalEntry
    {
        return DB::transaction(function () use ($payment, $departmentId, $costCenterId) {
            $existingEntry = JournalEntry::where('reference_type', PayablePayment::class)
                ->where('reference_id', $payment->id)
                ->first();

            $departmentId = $departmentId ?? \App\Models\Department::first()?->id ?? 1;
            $costCenterId = $costCenterId ?? \App\Models\CostCenter::first()?->id ?? 1;

            $cashBankAccountId = $payment->bank_account_id;
            if (!$cashBankAccountId) {
                throw new InvalidArgumentException('Bank account is required for payable payment');
            }

            $payableAccount = $this->findPayableAccount($payment->company_id);
            if (!$payableAccount) {
                throw new InvalidArgumentException('Accounts Payable account not found. Please create an account with code starting with 21% or name containing "Utang Usaha"');
            }

            $totalPayment = (float) $payment->total_payment;
            $otherCosts = (float) ($payment->other_costs ?? 0);
            
            $totalDiscount = (float) $payment->items()->sum('discount_amount');
            $totalWriteOff = (float) $payment->items()->sum('write_off_amount');
            $totalSetPayment = (float) $payment->items()->sum('set_payment');

            $payableDebitAmount = $totalSetPayment + $totalDiscount + $totalWriteOff;

            if ($existingEntry) {
                $existingEntry->update([
                    'date' => $payment->payment_date,
                    'reference_no' => $payment->reference_no ?? $payment->payment_number,
                    'description' => $payment->description ?? __('Payable Payment :number', ['number' => $payment->payment_number]),
                    'amount' => $totalPayment,
                    'total_amount' => $totalPayment,
                    'updated_by_user_id' => Auth::id(),
                ]);

                $existingEntry->items()->delete();

                // Cash/Bank Account - Credit total payment
                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $cashBankAccountId,
                    'debit' => 0,
                    'credit' => $totalPayment,
                    'notes' => __('Cash/Bank - Payment to :supplier', ['supplier' => $payment->supplier->name ?? '']),
                    'cost_center_id' => $costCenterId,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $payableAccount->id,
                    'debit' => $payableDebitAmount,
                    'credit' => 0,
                    'notes' => __('Payable - Payment :number', ['number' => $payment->payment_number]),
                    'cost_center_id' => $costCenterId,
                ]);

                if ($otherCosts > 0 && $payment->other_costs_account_id) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $existingEntry->id,
                        'account_id' => $payment->other_costs_account_id,
                        'debit' => $otherCosts,
                        'credit' => 0,
                        'notes' => __('Other Costs - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }

                if ($totalDiscount > 0) {
                    $discountAccount = \App\Models\AccountMapping::getAccountMapping('payable_payment', 'discount', $payment->company_id);
                    if ($discountAccount) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $existingEntry->id,
                            'account_id' => $discountAccount->id,
                            'debit' => 0,
                            'credit' => $totalDiscount,
                            'notes' => __('Discount - Payment :number', ['number' => $payment->payment_number]),
                            'cost_center_id' => $costCenterId,
                        ]);
                    }
                }

                if ($totalWriteOff > 0) {
                    $writeOffAccount = \App\Models\AccountMapping::getAccountMapping('payable_payment', 'write_off', $payment->company_id);
                    if ($writeOffAccount) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $existingEntry->id,
                            'account_id' => $writeOffAccount->id,
                            'debit' => 0,
                            'credit' => $totalWriteOff,
                            'notes' => __('Write Off - Payment :number', ['number' => $payment->payment_number]),
                            'cost_center_id' => $costCenterId,
                        ]);
                    }
                }

                return $existingEntry;
            }

            $codeService = app(CodeGeneratorService::class);
            $entryNumber = $codeService->generateCode('journal_entry', $payment->company_id);

            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $payment->payment_date,
                'reference_no' => $payment->reference_no ?? $payment->payment_number,
                'description' => $payment->description ?? __('Payable Payment :number', ['number' => $payment->payment_number]),
                'amount' => $totalPayment,
                'total_amount' => $totalPayment,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'pembayaran_utang',
                'reference_type' => PayablePayment::class,
                'reference_id' => $payment->id,
                'cash_bank_transaction_id' => null,
                'department_id' => $departmentId,
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'company_id' => $payment->company_id,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $cashBankAccountId,
                'debit' => 0,
                'credit' => $totalPayment,
                'notes' => __('Cash/Bank - Payment to :supplier', ['supplier' => $payment->supplier->name ?? '']),
                'cost_center_id' => $costCenterId,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payableAccount->id,
                'debit' => $payableDebitAmount,
                'credit' => 0,
                'notes' => __('Payable - Payment :number', ['number' => $payment->payment_number]),
                'cost_center_id' => $costCenterId,
            ]);

            if ($otherCosts > 0 && $payment->other_costs_account_id) {
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $payment->other_costs_account_id,
                    'debit' => $otherCosts,
                    'credit' => 0,
                    'notes' => __('Other Costs - Payment :number', ['number' => $payment->payment_number]),
                    'cost_center_id' => $costCenterId,
                ]);
            }

            if ($totalDiscount > 0) {
                $discountAccount = \App\Models\AccountMapping::getAccountMapping('payable_payment', 'discount', $payment->company_id);
                if ($discountAccount) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $discountAccount->id,
                        'debit' => 0,
                        'credit' => $totalDiscount,
                        'notes' => __('Discount - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }
            }

            if ($totalWriteOff > 0) {
                $writeOffAccount = \App\Models\AccountMapping::getAccountMapping('payable_payment', 'write_off', $payment->company_id);
                if ($writeOffAccount) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $writeOffAccount->id,
                        'debit' => 0,
                        'credit' => $totalWriteOff,
                        'notes' => __('Write Off - Payment :number', ['number' => $payment->payment_number]),
                        'cost_center_id' => $costCenterId,
                    ]);
                }
            }

            return $journalEntry;
        });
    }

    /**
     * Find default Accounts Receivable account (code 11%)
     */
    private function findReceivableAccount(?int $companyId = null): ?Account
    {
        $selectedCompanyId = $companyId ?? session('selected_company_id');
        
        $query = Account::where('is_header', false)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '11%')
                    ->orWhere('name', 'like', '%Piutang Usaha%');
            });

        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $query->where(function ($q) use ($selectedCompanyId) {
                $q->where('company_id', $selectedCompanyId)
                    ->orWhereNull('company_id');
            });
        }

        return $query->orderBy('code')->first();
    }

    /**
     * Find default Accounts Payable account (code 21%)
     */
    private function findPayableAccount(?int $companyId = null): ?Account
    {
        $selectedCompanyId = $companyId ?? session('selected_company_id');
        
        $query = Account::where('is_header', false)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '21%')
                    ->orWhere('name', 'like', '%Utang Usaha%');
            });

        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $query->where(function ($q) use ($selectedCompanyId) {
                $q->where('company_id', $selectedCompanyId)
                    ->orWhereNull('company_id');
            });
        }

        return $query->orderBy('code')->first();
    }

    /**
     * Delete journal entry for Receivable Payment
     */
    public function deleteJournalEntryForReceivablePayment(ReceivablePayment $payment): void
    {
        $journalEntry = JournalEntry::where('reference_type', ReceivablePayment::class)
            ->where('reference_id', $payment->id)
            ->first();

        if ($journalEntry) {
            $journalEntry->items()->delete();
            $journalEntry->delete();
        }
    }

    /**
     * Delete journal entry for Payable Payment
     */
    public function deleteJournalEntryForPayablePayment(PayablePayment $payment): void
    {
        $journalEntry = JournalEntry::where('reference_type', PayablePayment::class)
            ->where('reference_id', $payment->id)
            ->first();

        if ($journalEntry) {
            $journalEntry->items()->delete();
            $journalEntry->delete();
        }
    }

    /**
     * Update outstanding journal entry for Sales Invoice
     */
    public function updateOutstandingJournalEntryForSalesInvoice(\App\Models\SalesInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $existingEntry = JournalEntry::where('reference_type', SalesInvoice::class)
                ->where('reference_id', $invoice->id)
                ->where('sub_module', 'piutang_outstanding')
                ->first();

            $outstandingAmount = (float) $invoice->outstanding_amount;

            if ($outstandingAmount <= 0) {
                if ($existingEntry) {
                    $existingEntry->items()->delete();
                    $existingEntry->delete();
                }
                return;
            }

            $receivableAccount = $this->findReceivableAccount($invoice->company_id);
            if (!$receivableAccount) {
                return; 
            }

            $revenueAccount = $this->findRevenueAccount($invoice->company_id);
            if (!$revenueAccount) {
                return;
            }

            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            if ($existingEntry) {
                $existingEntry->update([
                    'amount' => $outstandingAmount,
                    'total_amount' => $outstandingAmount,
                    'date' => $invoice->date,
                    'updated_by_user_id' => Auth::id() ?? 1,
                ]);

                $existingEntry->items()->delete();

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $receivableAccount->id,
                    'debit' => $outstandingAmount,
                    'credit' => 0,
                    'notes' => __('Receivable - Invoice :number', ['number' => $invoice->invoice_number]),
                    'cost_center_id' => $costCenterId,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $revenueAccount->id,
                    'debit' => 0,
                    'credit' => $outstandingAmount,
                    'notes' => __('Revenue - Invoice :number', ['number' => $invoice->invoice_number]),
                    'cost_center_id' => $costCenterId,
                ]);
            } else {
                $codeService = app(CodeGeneratorService::class);
                $entryNumber = $codeService->generateCode('journal_entry', $invoice->company_id);

                $departmentId = \App\Models\Department::first()?->id ?? 1;

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
                    'posted_by_user_id' => Auth::id() ?? 1,
                    'posted_at' => now(),
                    'company_id' => $invoice->company_id,
                    'created_by_user_id' => Auth::id() ?? 1,
                    'updated_by_user_id' => Auth::id() ?? 1,
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
            }
        });
    }

    /**
     * Update outstanding journal entry for Purchase Invoice
     */
    public function updateOutstandingJournalEntryForPurchaseInvoice(\App\Models\PurchaseInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $existingEntry = JournalEntry::where('reference_type', PurchaseInvoice::class)
                ->where('reference_id', $invoice->id)
                ->where('sub_module', 'utang_outstanding')
                ->first();

            $outstandingAmount = (float) $invoice->outstanding_amount;

            if ($outstandingAmount <= 0) {
                if ($existingEntry) {
                    $existingEntry->items()->delete();
                    $existingEntry->delete();
                }
                return;
            }

            $payableAccount = $this->findPayableAccount($invoice->company_id);
            if (!$payableAccount) {
                return;
            }

            $expenseAccount = $this->findExpenseAccount($invoice->company_id);
            if (!$expenseAccount) {
                return;
            }

            $costCenterId = \App\Models\CostCenter::first()?->id ?? 1;

            if ($existingEntry) {
                $existingEntry->update([
                    'amount' => $outstandingAmount,
                    'total_amount' => $outstandingAmount,
                    'date' => $invoice->date,
                    'updated_by_user_id' => Auth::id() ?? 1,
                ]);

                $existingEntry->items()->delete();

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $expenseAccount->id,
                    'debit' => $outstandingAmount,
                    'credit' => 0,
                    'notes' => __('Expense - Invoice :number', ['number' => $invoice->invoice_number]),
                    'cost_center_id' => $costCenterId,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $existingEntry->id,
                    'account_id' => $payableAccount->id,
                    'debit' => 0,
                    'credit' => $outstandingAmount,
                    'notes' => __('Payable - Invoice :number', ['number' => $invoice->invoice_number]),
                    'cost_center_id' => $costCenterId,
                ]);
            } else {
                $codeService = app(CodeGeneratorService::class);
                $entryNumber = $codeService->generateCode('journal_entry', $invoice->company_id);

                $departmentId = \App\Models\Department::first()?->id ?? 1;

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
                    'posted_by_user_id' => Auth::id() ?? 1,
                    'posted_at' => now(),
                    'company_id' => $invoice->company_id,
                    'created_by_user_id' => Auth::id() ?? 1,
                    'updated_by_user_id' => Auth::id() ?? 1,
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
            }
        });
    }

    /**
     * Find Revenue account (code 4%)
     */
    private function findRevenueAccount(?int $companyId = null): ?Account
    {
        $query = Account::where('is_header', false)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '4%')
                    ->orWhere('name', 'like', '%Pendapatan%')
                    ->orWhere('name', 'like', '%Revenue%');
            });

        if ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)
                    ->orWhereNull('company_id');
            });
        }

        return $query->orderBy('code')->first();
    }

    /**
     * Find Expense account (code 5%)
     */
    private function findExpenseAccount(?int $companyId = null): ?Account
    {
        $query = Account::where('is_header', false)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('name', 'like', '%Beban%')
                    ->orWhere('name', 'like', '%Expense%');
            });

        if ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)
                    ->orWhereNull('company_id');
            });
        }

        return $query->orderBy('code')->first();
    }
}


