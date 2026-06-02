<?php

namespace App\Services;

use App\Models\Account;
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
    public function importFromExcel(string $filePath, int $bankAccountId, ?int $companyId): BankReconciliation
    {
        $bankAccount = BankAccount::findOrFail($bankAccountId);
        $bankLines = $this->readBankStatement($filePath);

        if (empty($bankLines)) {
            throw new \RuntimeException(__('No data rows found in the uploaded file.'));
        }

        return DB::transaction(function () use ($bankLines, $bankAccount, $companyId) {
            $totalDebit = collect($bankLines)->sum('debit');
            $totalCredit = collect($bankLines)->sum('credit');
            $userId = Auth::id();

            $reconciliation = BankReconciliation::create([
                'statement_date' => now()->format('Y-m-d'),
                'statement_balance' => (string) $totalCredit,
                'book_balance' => (string) $totalDebit,
                'reconciliation_date' => now()->format('Y-m-d'),
                'status' => 'in_progress',
                'reconciled_at' => null,
                'difference' => (string) abs(round($totalDebit - $totalCredit, 2)),
                'bank_account_id' => $bankAccount->id,
                'reconciled_by_user_id' => null,
                'company_id' => $companyId ?? $bankAccount->company_id,
                'created_by_user_id' => $userId,
            ]);

            $matched = 0;
            $unmatched = 0;

            foreach ($bankLines as $line) {
                $suggestion = $this->findMatch($line, $bankAccount, $companyId);

                $item = BankReconciliationItem::create([
                    'bank_reconciliation_id' => $reconciliation->id,
                    'type' => $line['type'],
                    'bank_date' => $line['date'],
                    'bank_description' => $line['description'],
                    'bank_debit' => $line['debit'],
                    'bank_credit' => $line['credit'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'suggested_invoice_id' => $suggestion['invoice_id'],
                    'suggested_invoice_type' => $suggestion['invoice_type'],
                    'suggested_invoice_amount' => $suggestion['amount'],
                    'match_status' => $suggestion['status'],
                ]);

                if ($suggestion['status'] === 'matched') {
                    $matched++;

                    // Auto-create payment for perfect matches
                    $this->createPayment($item, $line, $bankAccount, $suggestion, $companyId, $userId);
                } elseif ($suggestion['status'] === 'suggested') {
                    // Suggested but needs confirmation — leave for user review
                } else {
                    $unmatched++;
                }
            }

            // Update reconciliation summary
            $reconciliation->update([
                'difference' => (string) abs(round($totalDebit - $totalCredit, 2)),
                'status' => $unmatched === 0 ? 'completed' : 'in_progress',
                'reconciled_at' => $unmatched === 0 ? now() : null,
                'reconciled_by_user_id' => $unmatched === 0 ? $userId : null,
            ]);

            return $reconciliation;
        });
    }

    /**
     * Confirm a suggested match — creates the payment.
     */
    public function confirmMatch(BankReconciliationItem $item): void
    {
        if ($item->match_status !== 'suggested') {
            throw new \RuntimeException(__('Only suggested items can be confirmed.'));
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

        if ($suggestion['invoice_type'] === SalesInvoice::class) {
            // Incoming — customer payment
            $payment = ReceivablePayment::create([
                'payment_date' => $line['date'],
                'reference_no' => 'BANK-RECON-' . $item->bank_reconciliation_id,
                'total_payment' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'customer_id' => SalesInvoice::find($suggestion['invoice_id'])->customer_id,
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
            $invoice = SalesInvoice::find($suggestion['invoice_id']);
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
        }

        if ($suggestion['invoice_type'] === PurchaseInvoice::class) {
            $payment = PayablePayment::create([
                'payment_date' => $line['date'],
                'reference_no' => 'BANK-RECON-' . $item->bank_reconciliation_id,
                'total_payment' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'supplier_id' => PurchaseInvoice::find($suggestion['invoice_id'])->supplier_id,
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
            $invoice = PurchaseInvoice::find($suggestion['invoice_id']);
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
        }
    }

    /**
     * Find a matching invoice for a bank statement line.
     *
     * @return array{invoice_id: ?int, invoice_type: ?string, amount: float, status: string}
     */
    private function findMatch(array $line, BankAccount $bankAccount, ?int $companyId): array
    {
        $amount = $line['debit'] > 0 ? $line['debit'] : $line['credit'];
        if ($amount <= 0) {
            return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
        }

        $tolerance = round($amount * 0.02, 2); // 2% tolerance
        $minAmount = round($amount - $tolerance, 2);
        $maxAmount = round($amount + $tolerance, 2);

        if ($line['type'] === 'incoming') {
            return $this->searchSalesInvoices($minAmount, $maxAmount, $amount, $line['date'], $companyId);
        }

        if ($line['type'] === 'outgoing') {
            return $this->searchPurchaseInvoices($minAmount, $maxAmount, $amount, $line['date'], $companyId);
        }

        return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
    }

    private function searchSalesInvoices(float $min, float $max, float $target, string $date, ?int $companyId): array
    {
        $candidates = SalesInvoice::where('outstanding_amount', '>', 0)
            ->whereBetween('outstanding_amount', [$min, $max])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderByRaw('ABS(outstanding_amount::numeric - ?)', [$target])
            ->limit(5)
            ->get();

        if ($candidates->isEmpty()) {
            return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
        }

        if ($candidates->count() === 1) {
            $inv = $candidates->first();
            return [
                'invoice_id' => $inv->id,
                'invoice_type' => SalesInvoice::class,
                'amount' => (float) $inv->outstanding_amount,
                'status' => 'matched',
            ];
        }

        // Multiple candidates — suggest best match (closest amount)
        $inv = $candidates->first();
        return [
            'invoice_id' => $inv->id,
            'invoice_type' => SalesInvoice::class,
            'amount' => (float) $inv->outstanding_amount,
            'status' => 'suggested',
        ];
    }

    private function searchPurchaseInvoices(float $min, float $max, float $target, string $date, ?int $companyId): array
    {
        $candidates = PurchaseInvoice::where('outstanding_amount', '>', 0)
            ->whereBetween('outstanding_amount', [$min, $max])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderByRaw('ABS(outstanding_amount::numeric - ?)', [$target])
            ->limit(5)
            ->get();
        if ($candidates->isEmpty()) {
            return ['invoice_id' => null, 'invoice_type' => null, 'amount' => 0, 'status' => 'unmatched'];
        }

        if ($candidates->count() === 1) {
            $inv = $candidates->first();
            return [
                'invoice_id' => $inv->id,
                'invoice_type' => PurchaseInvoice::class,
                'amount' => (float) $inv->outstanding_amount,
                'status' => 'matched',
            ];
        }

        $inv = $candidates->first();
        return [
            'invoice_id' => $inv->id,
            'invoice_type' => PurchaseInvoice::class,
            'amount' => (float) $inv->outstanding_amount,
            'status' => 'suggested',
        ];
    }

    /**
     * Read bank statement Excel.
     *
     * Template: Date | Description | Debit (Outgoing) | Credit (Incoming)
     *
     * @return array<int, array{type: string, date: string, description: string, debit: float, credit: float}>
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

        $getCol = function (array $aliases) use ($normalized): int {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $normalized, true);
                if ($idx !== false) {
                    return $idx;
                }
            }
            throw new \RuntimeException(__('Required column not found: :col.', ['col' => $aliases[0]]));
        };

        $colDate = $getCol(['tanggal', 'date', 'tgl', 'transactiondate']);
        $colDesc = $getCol(['deskripsi', 'description', 'keterangan', 'uraian', 'narration']);
        $colDebit = $getCol(['debit', 'debet', 'outgoing', 'pengeluaran', 'debit(outgoing)', 'debitoutgoing']);
        $colCredit = $getCol(['credit', 'kredit', 'incoming', 'pemasukan', 'credit(incoming)', 'creditincoming']);

        $lines = [];
        $errors = [];

        for ($i = 1, $len = count($rows); $i < $len; $i++) {
            $rowNumber = $i + 1;
            $row = $rows[$i];

            $date = trim((string) ($row[$colDate] ?? ''));
            $description = trim((string) ($row[$colDesc] ?? ''));
            $debitRaw = (float) ($row[$colDebit] ?? 0);
            $creditRaw = (float) ($row[$colCredit] ?? 0);

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
     */
    public function forceMatch(BankReconciliationItem $item, int $invoiceId, string $invoiceType): void
    {
        $invoice = $invoiceType::findOrFail($invoiceId);

        DB::transaction(function () use ($item, $invoice, $invoiceType) {
            $bankAccount = $item->bankReconciliation->bankAccount ?? BankAccount::findOrFail($item->bankReconciliation->bank_account_id);

            $line = [
                'type' => $item->type,
                'date' => $item->bank_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'description' => $item->bank_description,
                'debit' => (float) $item->bank_debit,
                'credit' => (float) $item->bank_credit,
            ];

            $amount = $invoiceType === SalesInvoice::class
                ? (float) $invoice->outstanding_amount
                : (float) $invoice->outstanding_amount;

            $suggestion = [
                'invoice_id' => $invoice->id,
                'invoice_type' => $invoiceType,
                'amount' => $amount,
                'status' => 'matched',
            ];

            $this->createPayment(
                $item,
                $line,
                $bankAccount,
                $suggestion,
                $item->bankReconciliation->company_id,
                Auth::id()
            );

            $item->update([
                'match_status' => 'matched',
                'suggested_invoice_id' => $invoice->id,
                'suggested_invoice_type' => $invoiceType,
                'suggested_invoice_amount' => $amount,
            ]);
        });
    }

    /**
     * Unmatch a matched or suggested item, reverting it to unmatched.
     */
    public function unmatch(BankReconciliationItem $item): void
    {
        if ($item->match_status === 'matched') {
            throw new \RuntimeException(__('Matched items with payments cannot be unmatched. Please delete the payment first.'));
        }

        $item->update([
            'match_status' => 'unmatched',
            'suggested_invoice_id' => null,
            'suggested_invoice_type' => null,
            'suggested_invoice_amount' => null,
        ]);
    }
}
