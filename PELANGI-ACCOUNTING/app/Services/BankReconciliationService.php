<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use App\Models\BankReconciliationItem;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\PayablePayment;
use App\Models\PayablePaymentItem;
use App\Models\PurchaseInvoice;
use App\Models\ReceivablePayment;
use App\Models\ReceivablePaymentItem;
use App\Models\SalesInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BankReconciliationService
{
    /**
     * Import bank statement from Excel, auto-match against invoices, create reconciliations.
     *
     * Template columns: Date | Description | Debit (outgoing) | Credit (incoming)
     */
    public function importFromExcel(string $filePath, int $bankAccountId, ?int $companyId): array
    {
        $bankAccount = BankAccount::findOrFail($bankAccountId);
        $bankLines = $this->readBankStatement($filePath);

        if (empty($bankLines)) {
            throw new \RuntimeException(__('No data rows found in the uploaded file.'));
        }

        return DB::transaction(function () use ($bankLines, $bankAccount, $companyId) {
            $totalDebit = collect($bankLines)->sum('debit');
            $totalCredit = collect($bankLines)->sum('credit');
            $statementBalance = round($totalCredit - $totalDebit, 2);
            $userId = Auth::id();

            $reconciliation = BankReconciliation::create([
                'statement_date' => now()->format('Y-m-d'),
                'statement_balance' => (string) $statementBalance,
                'book_balance' => '0',
                'reconciliation_date' => now()->format('Y-m-d'),
                'status' => 'in_progress',
                'reconciled_at' => null,
                'difference' => '0',
                'bank_account_id' => $bankAccount->id,
                'reconciled_by_user_id' => null,
                'company_id' => $companyId ?? $bankAccount->company_id,
                'created_by_user_id' => $userId,
            ]);

            $matched = 0;
            $unmatched = 0;
            $skipped = 0;
            $journals = 0;

            foreach ($bankLines as $line) {
                // Duplicate check: date + amount + description + ref + account_code + invoice_no
                $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
                $desc = $line['description'] ?: null;
                $ref = $line['reference_no'] ?: null;
                $acctCode = $line['account_code'] ?: null;
                $invNo = $line['invoice_no'] ?: null;

                $isDuplicate = BankReconciliationItem::whereHas('bankReconciliation', function ($q) use ($bankAccount) {
                    $q->where('bank_account_id', $bankAccount->id);
                })
                    ->where('bank_date', $line['date'])
                    ->where(fn ($q) => $q->where('bank_debit', $amount)->orWhere('bank_credit', $amount))
                    ->where(fn ($q) => $desc === null ? $q->whereNull('bank_description') : $q->where('bank_description', $desc))
                    ->where(fn ($q) => $ref === null ? $q->whereNull('reference_no') : $q->where('reference_no', $ref))
                    ->where(fn ($q) => $acctCode === null ? $q->whereNull('account_code') : $q->where('account_code', $acctCode))
                    ->where(fn ($q) => $invNo === null ? $q->whereNull('invoice_no') : $q->where('invoice_no', $invNo))
                    ->exists();

                if ($isDuplicate) {
                    $skipped++;
                    continue;
                }

                // Determine action: invoice payment or journal
                $invoiceNo = $line['invoice_no'] ?? null;
                $bankAmount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
                $suggestion = ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];

                if ($invoiceNo) {
                    // Find invoice by invoice_number and compare amounts
                    $suggestion = $this->findInvoiceByNumber($invoiceNo, $companyId, $bankAmount);
                } else {
                    $suggestion = ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
                }

                $item = BankReconciliationItem::create([
                    'bank_reconciliation_id' => $reconciliation->id,
                    'type' => $line['type'],
                    'bank_date' => $line['date'],
                    'bank_description' => $line['description'],
                    'bank_debit' => $line['debit'],
                    'bank_credit' => $line['credit'],
                    'reference_no' => $line['reference_no'] ?? null,
                    'account_code' => $line['account_code'] ?? null,
                    'invoice_no' => $line['invoice_no'] ?? null,
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'suggested_invoice_id' => $suggestion['invoice_id'],
                    'suggested_invoice_type' => $suggestion['invoice_type'],
                    'suggested_invoice_amount' => $suggestion['amount'],
                    // Always 'suggested' if invoice found (regardless of amount match), so user can review
                    'match_status' => $suggestion['invoice_id'] ? 'suggested' : $suggestion['status'],
                ]);

                // Just count statuses for summary
                if ($suggestion['invoice_id']) {
                    $matched++; // counted as suggested match
                } elseif ($suggestion['status'] === 'partially_matched') {
                    $unmatched++;
                } elseif ($suggestion['status'] === 'unmatched') {
                    $unmatched++;
                } else {
                    $unmatched++;
                }
            }

            // Calculate unmatched amount (items that couldn't be auto-matched or manually matched)
            $unmatchedAmount = 0;
            foreach ($bankLines as $line) {
                $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
                $invNo = $line['invoice_no'] ?? null;
                $bankAmount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
                $suggestion = ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];

                if ($invNo) {
                    $suggestion = $this->findInvoiceByNumber($invNo, $companyId, $bankAmount);
                }

                // If no invoice match, invoice already paid, or amount mismatch (partially_matched), it's unmatched
                if ($suggestion['status'] !== 'matched' || !$suggestion['invoice_id']) {
                    $unmatchedAmount += $amount;
                }
            }

            // Update reconciliation summary
            $imported = $matched + $unmatched + $journals;
            if ($imported === 0) {
                $reconciliation->delete();
                throw new \Exception('All lines are duplicates. Nothing imported.');
            }

            $reconciliation->update([
                'difference' => (string) round($unmatchedAmount, 2),
                'status' => 'in_progress', // Always in_progress after import; completed after Save Matches
                'reconciled_at' => null,
                'reconciled_by_user_id' => null,
            ]);

            return ['reconciliation' => $reconciliation, 'skipped' => $skipped];
        });
    }

    /**
     * Confirm a suggested match — creates the payment.
     */
    public function confirmMatch(BankReconciliationItem $item): void
    {
        if (! in_array($item->match_status, ['suggested', 'partially_matched'])) {
            throw new \RuntimeException(__('Only suggested or partially matched items can be confirmed.'));
        }

        $bankAccount = $item->bankReconciliation->bankAccount ?? BankAccount::findOrFail($item->bankReconciliation->bank_account_id);

        $line = [
            'type' => $item->type,
            'date' => $item->bank_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
            'description' => $item->bank_description,
            'debit' => (float) $item->bank_debit,
            'credit' => (float) $item->bank_credit,
        ];

        $suggestion = [
            'invoice_id' => $item->suggested_invoice_id,
            'invoice_type' => $item->suggested_invoice_type,
            'amount' => (float) $item->suggested_invoice_amount,
            'status' => 'matched',
        ];

        DB::transaction(function () use ($item, $line, $bankAccount, $suggestion) {
            $this->createPayment(
                $item,
                $line,
                $bankAccount,
                $suggestion,
                $item->bankReconciliation->company_id,
                Auth::id()
            );
            $item->update(['match_status' => 'matched']);
        });
    }

    private function createPayment(BankReconciliationItem $item, array $line, BankAccount $bankAccount, array $suggestion, ?int $companyId, int $userId): void
    {
        if (! $suggestion['invoice_id']) {
            return;
        }

        $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
        $bankCoa = $bankAccount->coaAccount;

        if ($suggestion['invoice_type'] === SalesInvoice::class) {
            // Incoming — customer payment
            $invoice = SalesInvoice::find($suggestion['invoice_id']);

            $payment = ReceivablePayment::create([
                'payment_date' => $line['date'],
                'reference_no' => 'BANK-RECON-' . $item->bank_reconciliation_id,
                'total_payment' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'customer_id' => $invoice->customer_id,
                'bank_account_id' => $bankAccount->id,
                'company_id' => $companyId,
                'created_by_user_id' => $userId,
                'updated_by_user_id' => $userId,
            ]);

            ReceivablePaymentItem::create([
                'date' => $line['date'],
                'amount' => $amount,
                'paid_amount' => $amount,
                'set_payment' => $amount,
                'receivable_payment_id' => $payment->id,
                'sales_invoice_id' => $suggestion['invoice_id'],
            ]);

            // Update invoice
            $newPaidAmount = (float) $invoice->paid_amount + $amount;
            $newOutstanding = (float) $invoice->total_amount - $newPaidAmount;

            $invoice->paid_amount = $newPaidAmount;
            $invoice->outstanding_amount = max(0, $newOutstanding);
            $invoice->is_paid = $newOutstanding <= 0;

            if ($invoice->is_paid) {
                $invoice->status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'sent';
            }

            $invoice->save();

            // Create draft journal entry: Debit Bank, Credit AR
            if ($bankCoa) {
                $arAccount = AccountMapping::getAccountMapping('sales_invoice', 'accounts_receivable', $companyId);
                if ($arAccount) {
                    $entryNumber = 'BANK-RECON-' . $item->bank_reconciliation_id . '-' . substr(uniqid(), -6);
                    $journalEntry = JournalEntry::create([
                        'entry_number' => $entryNumber,
                        'date' => $line['date'],
                        'reference_no' => $line['reference_no'] ?: $invoice->invoice_number,
                        'description' => 'Pembayaran ' . $invoice->invoice_number,
                        'amount' => $amount,
                        'total_amount' => $amount,
                        'status' => 'draft',
                        'is_posted' => false,
                        'sub_module' => 'bank_reconciliation',
                        'reference_type' => ReceivablePayment::class,
                        'reference_id' => $payment->id,
                        'company_id' => $companyId,
                        'created_by_user_id' => $userId,
                    ]);
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $bankCoa->id,
                        'debit' => $amount,
                        'credit' => 0,
                        'notes' => 'Bank - ' . $bankAccount->account_name,
                    ]);
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $arAccount->id,
                        'debit' => 0,
                        'credit' => $amount,
                        'notes' => 'Piutang - ' . $invoice->invoice_number,
                    ]);
                }
            }
        }

        if ($suggestion['invoice_type'] === PurchaseInvoice::class) {
            $invoice = PurchaseInvoice::find($suggestion['invoice_id']);

            $payment = PayablePayment::create([
                'payment_date' => $line['date'],
                'reference_no' => 'BANK-RECON-' . $item->bank_reconciliation_id,
                'total_payment' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'supplier_id' => $invoice->supplier_id,
                'bank_account_id' => $bankAccount->id,
                'company_id' => $companyId,
                'created_by_user_id' => $userId,
                'updated_by_user_id' => $userId,
            ]);

            PayablePaymentItem::create([
                'date' => $line['date'],
                'amount' => $amount,
                'paid_amount' => $amount,
                'set_payment' => $amount,
                'payable_payment_id' => $payment->id,
                'purchase_invoice_id' => $suggestion['invoice_id'],
            ]);

            // Update invoice
            $newPaidAmount = (float) $invoice->paid_amount + $amount;
            $newOutstanding = (float) $invoice->total - $newPaidAmount;

            $invoice->paid_amount = $newPaidAmount;
            $invoice->outstanding_amount = max(0, $newOutstanding);
            $invoice->is_paid = $newOutstanding <= 0;

            if ($invoice->is_paid) {
                $invoice->status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'received';
            }

            $invoice->save();

            // Create draft journal entry: Debit AP, Credit Bank
            if ($bankCoa) {
                $apAccount = AccountMapping::getAccountMapping('purchase_invoice', 'accounts_payable', $companyId);
                if ($apAccount) {
                    $entryNumber = 'BANK-RECON-' . $item->bank_reconciliation_id . '-' . substr(uniqid(), -6);
                    $journalEntry = JournalEntry::create([
                        'entry_number' => $entryNumber,
                        'date' => $line['date'],
                        'reference_no' => $line['reference_no'] ?: $invoice->invoice_number,
                        'description' => 'Pembayaran ' . $invoice->invoice_number,
                        'amount' => $amount,
                        'total_amount' => $amount,
                        'status' => 'draft',
                        'is_posted' => false,
                        'sub_module' => 'bank_reconciliation',
                        'reference_type' => PayablePayment::class,
                        'reference_id' => $payment->id,
                        'company_id' => $companyId,
                        'created_by_user_id' => $userId,
                    ]);
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $apAccount->id,
                        'debit' => $amount,
                        'credit' => 0,
                        'notes' => 'Utang - ' . $invoice->invoice_number,
                    ]);
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $bankCoa->id,
                        'debit' => 0,
                        'credit' => $amount,
                        'notes' => 'Bank - ' . $bankAccount->account_name,
                    ]);
                }
            }
        }
    }

    /**
     * Find invoice by invoice_number (Sales or Purchase).
     * Compares bank amount with invoice outstanding amount to determine match status.
     */
    private function findInvoiceByNumber(string $invoiceNo, ?int $companyId, float $bankAmount = 0): array
    {
        // Try SalesInvoice first
        $query = SalesInvoice::where('invoice_number', $invoiceNo);
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        $invoice = $query->first();

        if ($invoice) {
            if ($invoice->outstanding_amount <= 0) {
                return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
            }
            $invoiceAmount = (float) $invoice->outstanding_amount;
            $status = $this->compareAmounts($bankAmount, $invoiceAmount);
            return [
                'invoice_id' => $invoice->id,
                'invoice_type' => SalesInvoice::class,
                'amount' => $invoiceAmount,
                'status' => $status,
            ];
        }

        // Try PurchaseInvoice
        $query = PurchaseInvoice::where('invoice_number', $invoiceNo);
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        $invoice = $query->first();

        if ($invoice) {
            if ($invoice->outstanding_amount <= 0) {
                return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
            }
            $invoiceAmount = (float) $invoice->outstanding_amount;
            $status = $this->compareAmounts($bankAmount, $invoiceAmount);
            return [
                'invoice_id' => $invoice->id,
                'invoice_type' => PurchaseInvoice::class,
                'amount' => $invoiceAmount,
                'status' => $status,
            ];
        }

        return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
    }

    /**
     * Compare bank amount with invoice amount.
     * Returns 'matched' if equal, 'partially_matched' if different, 'unmatched' if no invoice.
     */
    private function compareAmounts(float $bankAmount, float $invoiceAmount): string
    {
        if ($bankAmount <= 0 || $invoiceAmount <= 0) {
            return 'unmatched';
        }

        // Use epsilon of 1.00 (1 IDR) to handle rounding differences in bank statements
        $epsilon = 1.00;
        if (abs($bankAmount - $invoiceAmount) < $epsilon) {
            return 'matched';
        }

        return 'partially_matched';
    }

    /**
     * Create a general journal entry (journal umum) for bank transactions without invoices.
     */
    private function createJournalEntry(array $line, BankAccount $bankAccount, string $accountCode, ?int $companyId, int $userId, int $reconciliationId): void
    {
        $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
        $isIncoming = $line['type'] === 'incoming';

        // Find the contra account by code
        $contraAccount = Account::where('code', $accountCode)
            ->where('company_id', $companyId)
            ->where('is_header', false)
            ->first();

        if (!$contraAccount) {
            throw new \RuntimeException(__('Account with code ":code" not found.', ['code' => $accountCode]));
        }

        // Get bank COA from bank account relationship
        $bankCoa = $bankAccount->coaAccount;

        if (!$bankCoa) {
            throw new \RuntimeException(__('Bank account ":name" is not linked to a COA account. Please set the COA Account on the bank account master.', ['name' => $bankAccount->account_name]));
        }

        $entryNumber = 'BANK-RECON-' . $reconciliationId . '-' . substr(uniqid(), -6);

        $journalEntry = JournalEntry::create([
            'entry_number' => $entryNumber,
            'date' => $line['date'],
            'reference_no' => $line['reference_no'] ?: null,
            'description' => $line['description'] ?: 'Bank reconciliation - ' . ($isIncoming ? 'incoming' : 'outgoing'),
            'amount' => $amount,
            'total_amount' => $amount,
            'status' => 'draft',
            'is_posted' => false,
            'sub_module' => 'bank_reconciliation',
            'company_id' => $companyId,
            'created_by_user_id' => $userId,
        ]);

        if ($isIncoming) {
            // Incoming: debit bank, credit contra
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $bankCoa->id,
                'debit' => $amount,
                'credit' => 0,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $contraAccount->id,
                'debit' => 0,
                'credit' => $amount,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
        } else {
            // Outgoing: debit contra, credit bank
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $contraAccount->id,
                'debit' => $amount,
                'credit' => 0,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $bankCoa->id,
                'debit' => 0,
                'credit' => $amount,
                'notes' => $line['notes'] ?? $line['description'] ?? '',
            ]);
        }
    }

    /**
     * Read bank statement Excel.
     *
     * Template: Date | Description | Reference | Account Code | Invoice No | Debit (Outgoing) | Credit (Incoming)
     *
     * @return array<int, array{type: string, date: string, description: string, reference_no: string, account_code: string, invoice_no: string, debit: float, credit: float}>
     */
    private function readBankStatement(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (count($rows) < 2) {
            throw new \RuntimeException(__('The uploaded file is empty.'));
        }

        $headers = array_map(fn($h) => is_string($h) ? trim($h) : '', $rows[0]);
        $normalized = array_map(fn($h) => strtolower(str_replace(' ', '', $h)), $headers);

        $getCol = function (array $aliases) use ($normalized): ?int {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $normalized, true);
                if ($idx !== false) {
                    return $idx;
                }
            }
            return null;
        };

        $colDate = $getCol(['tanggal', 'date', 'tgl', 'transactiondate']);
        $colDesc = $getCol(['deskripsi', 'description', 'keterangan', 'uraian', 'narration']);
        $colDebit = $getCol(['debit', 'debet', 'outgoing', 'pengeluaran', 'debit(outgoing)', 'debitoutgoing']);
        $colCredit = $getCol(['kredit', 'credit', 'incoming', 'pemasukan', 'credit(incoming)', 'creditincoming']);
        $colRef = $getCol(['referensi', 'ref', 'reference', 'noreferensi', 'referenceno', 'nobukti']);
        $colAccountCode = $getCol(['kode_akun', 'kodeakun', 'accountcode', 'nocoa', 'coa', 'account_code']);
        $colAccountName = $getCol(['nama_akun', 'namaakun', 'accountname', 'nama_coa']);
        $colNotes = $getCol(['catatan', 'notes', 'keterangan']);
        $colInvoiceNo = $getCol(['invoice_no', 'invoiceno', 'noinvoice', 'no_invoice', 'nobonfaktur', 'faktur', 'nofaktur']);

        if ($colDate === null || $colDesc === null || ($colDebit === null && $colCredit === null)) {
            throw new \RuntimeException(__('Required columns not found. Need at least: Date, Description, and one of Debit/Credit.'));
        }

        $lines = [];
        $errors = [];

        for ($i = 1, $len = count($rows); $i < $len; $i++) {
            $rowNumber = $i + 1;
            $row = $rows[$i];

            $date = trim((string) ($row[$colDate] ?? ''));
            $description = trim((string) ($row[$colDesc] ?? ''));
            $referenceNo = $colRef !== null ? trim((string) ($row[$colRef] ?? '')) : '';
            $accountCode = $colAccountCode !== null ? trim((string) ($row[$colAccountCode] ?? '')) : '';
            $notes = $colNotes !== null ? trim((string) ($row[$colNotes] ?? '')) : '';
            $invoiceNo = $colInvoiceNo !== null ? trim((string) ($row[$colInvoiceNo] ?? '')) : '';
            $debitRaw = $colDebit !== null ? $this->parseAmount($row[$colDebit] ?? 0) : 0;
            $creditRaw = $colCredit !== null ? $this->parseAmount($row[$colCredit] ?? 0) : 0;

            if ($debitRaw == 0 && $creditRaw == 0) {
                continue;
            }

            if ($date === '') {
                $errors[] = __('Row :row: Date is empty.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw < 0 || $creditRaw < 0) {
                $errors[] = __('Row :row: Negative amounts are not allowed.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw > 0 && $creditRaw > 0) {
                $errors[] = __('Row :row: Cannot have both debit and credit.', ['row' => $rowNumber]);
                continue;
            }

            $lines[] = [
                'type' => $creditRaw > 0 ? 'incoming' : 'outgoing',
                'date' => $date,
                'description' => $description ?: null,
                'reference_no' => $referenceNo ?: null,
                'account_code' => $accountCode ?: null,
                'notes' => $notes ?: null,
                'invoice_no' => $invoiceNo ?: null,
                'debit' => round($debitRaw, 2),
                'credit' => round($creditRaw, 2),
            ];
        }

        if (! empty($errors)) {
            throw new \RuntimeException(implode("\n", $errors));
        }

        return $lines;
    }

    /**
     * Manually match an unmatched/reverted item to a specific invoice.
     * Only updates the match status; payment is created in processMatches().
     */
    public function forceMatch(BankReconciliationItem $item, int $invoiceId, string $invoiceType): void
    {
        $invoice = $invoiceType::findOrFail($invoiceId);

        $amount = $invoiceType === SalesInvoice::class
            ? (float) $invoice->outstanding_amount
            : (float) $invoice->outstanding_amount;

        $item->update([
            'match_status' => 'matched',
            'suggested_invoice_id' => $invoice->id,
            'suggested_invoice_type' => $invoiceType,
            'suggested_invoice_amount' => $amount,
        ]);
    }

    /**
     * Unmatch a matched or suggested item, reverting it to unmatched.
     * Note: If payment was already created (via Save Matches), delete the payment first.
     */
    public function unmatch(BankReconciliationItem $item): void
    {
        $item->update([
            'match_status' => 'unmatched',
            'suggested_invoice_id' => null,
            'suggested_invoice_type' => null,
            'suggested_invoice_amount' => null,
        ]);
    }

    /**
     * Recalculate the difference based on current match status of items.
     * Difference = sum of amounts for items with match_status != 'matched'
     */
    public function recalculateDifference(BankReconciliation $reconciliation): void
    {
        $amounts = $this->getCalculatedAmounts($reconciliation);
        $reconciliation->update([
            'difference' => (string) round($amounts['unmatched_amount'], 2),
        ]);
    }

    /**
     * Get calculated amounts for live display.
     * Returns: statement_total, matched_amount, unmatched_amount
     * For partially_matched: matched = invoice amount, unmatched = difference
     * For suggested/unmatched: full amount counts as unmatched
     */
    public function getCalculatedAmounts(BankReconciliation $reconciliation): array
    {
        $items = $reconciliation->items;

        $statementTotal = $items->sum(function ($item) {
            return $item->bank_debit > 0 ? (float) $item->bank_debit : (float) $item->bank_credit;
        });

        $matchedAmount = 0;
        $unmatchedAmount = 0;

        foreach ($items as $item) {
            $bankAmount = $item->bank_debit > 0 ? (float) $item->bank_debit : (float) $item->bank_credit;
            $invoiceAmount = (float) $item->suggested_invoice_amount;

            switch ($item->match_status) {
                case 'matched':
                    $matchedAmount += $bankAmount;
                    break;
                case 'partially_matched':
                    // Matched portion = invoice amount (or bank amount - difference)
                    $matchedAmount += $invoiceAmount;
                    // Unmatched = difference
                    $unmatchedAmount += abs($bankAmount - $invoiceAmount);
                    break;
                case 'suggested':
                case 'unmatched':
                default:
                    // Full amount unmatched
                    $unmatchedAmount += $bankAmount;
                    break;
            }
        }

        return [
            'statement_total' => round($statementTotal, 2),
            'matched_amount' => round($matchedAmount, 2),
            'unmatched_amount' => round($unmatchedAmount, 2),
        ];
    }

    /**
     * Process all matches: create payments for matched/partially_matched items,
     * create journal entries for unmatched items with account_code.
     * Call this when user clicks "Save Matches".
     */
    public function processMatches(BankReconciliation $reconciliation): array
    {
        $items = $reconciliation->items()->whereIn('match_status', ['matched', 'partially_matched', 'unmatched'])->get();
        $processed = 0;
        $errors = [];

        foreach ($items as $item) {
            try {
                $bankAccount = $reconciliation->bankAccount;
                $line = [
                    'type' => $item->type,
                    'date' => $item->bank_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    'description' => $item->bank_description,
                    'debit' => (float) $item->bank_debit,
                    'credit' => (float) $item->bank_credit,
                ];

                if (in_array($item->match_status, ['matched', 'partially_matched']) && $item->suggested_invoice_id) {
                    // Create payment for matched/partially_matched items
                    $suggestion = [
                        'invoice_id' => $item->suggested_invoice_id,
                        'invoice_type' => $item->suggested_invoice_type,
                        'amount' => (float) $item->suggested_invoice_amount,
                        'status' => 'matched',
                    ];

                    DB::transaction(function () use ($item, $line, $bankAccount, $suggestion, $reconciliation) {
                        $this->createPayment(
                            $item,
                            $line,
                            $bankAccount,
                            $suggestion,
                            $reconciliation->company_id,
                            Auth::id()
                        );
                        $item->update(['match_status' => 'matched']);
                    });
                } elseif ($item->match_status === 'unmatched' && $item->account_code) {
                    // Create journal entry for unmatched items with account_code
                    DB::transaction(function () use ($item, $line, $bankAccount, $reconciliation) {
                        $this->createJournalEntry(
                            $line,
                            $bankAccount,
                            $item->account_code,
                            $reconciliation->company_id,
                            Auth::id(),
                            $reconciliation->id
                        );
                        $item->update(['match_status' => 'matched']); // Mark as processed
                    });
                }
                $processed++;
            } catch (\Exception $e) {
                $errors[] = $item->bank_description . ': ' . $e->getMessage();
            }
        }

        // Recalculate difference after processing
        $this->recalculateDifference($reconciliation);

        // Check if all items are matched
        $remaining = $reconciliation->items()->where('match_status', '!=', 'matched')->count();
        if ($remaining === 0) {
            $reconciliation->update([
                'status' => 'completed',
                'reconciled_at' => now(),
                'reconciled_by_user_id' => Auth::id(),
            ]);
        }

        return ['processed' => $processed, 'errors' => $errors];
    }

    protected function parseAmount(mixed $value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }
        $str = trim((string) $value);
        if ($str === '') {
            return 0.0;
        }
        $dotCount = substr_count($str, '.');
        $commaCount = substr_count($str, ',');

        if ($dotCount > 1) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif ($commaCount > 1) {
            $str = str_replace(',', '', $str);
        } elseif ($dotCount === 1 && $commaCount === 1) {
            $lastComma = strrpos($str, ',');
            $lastDot = strrpos($str, '.');
            if ($lastComma > $lastDot) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif ($dotCount === 1 && $commaCount === 0) {
            $parts = explode('.', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace('.', '', $str);
            }
        } elseif ($commaCount === 1 && $dotCount === 0) {
            $parts = explode(',', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        }

        return (float) str_replace(' ', '', $str);
    }
}
