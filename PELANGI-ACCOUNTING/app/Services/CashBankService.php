<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AdvanceDisbursement;
use App\Models\AdvanceDisbursementItem;
use App\Models\AdvanceReceipt;
use App\Models\AdvanceReceiptItem;
use App\Models\BankAccount;
use App\Models\CashBankTransaction;
use App\Models\CashDisbursement;
use App\Models\CashDisbursementItem;
use App\Models\CashReceipt;
use App\Models\CashReceiptItem;
use App\Models\CashTransfer;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Services\CodeGeneratorService;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CashBankService
{
    public function createAdvanceReceipt(array $data): CashReceipt
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            $this->assertHasItems($items);

            $totalAmount = $this->calculateTotal($items);
            $this->assertPositiveAmount($totalAmount);
            $bankAccount = $this->resolveBankAccount($data);
            $this->ensureBankAccountHasLedgerAccount($bankAccount);
            $isPosted = $this->isPosted($data['status'] ?? null);

            $cashBankTx = $this->createCashBankTransaction([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'amount' => $totalAmount,
                'sub_module' => 'penerimaan_uang_muka',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'from_account_id' => null,
                'to_account_id' => $data['to_account_id'] ?? $bankAccount->id,
                'company_id' => $data['company_id'] ?? $bankAccount->company_id,
                'status' => $isPosted ? 'posted' : 'draft',
            ]);

            $receiptNumber = $data['receipt_number'] ?? null;
            $codeService = app(CodeGeneratorService::class);
            $companyId = $data['company_id'] ?? $bankAccount->company_id;
            if (empty($receiptNumber)) {
                $receiptNumber = $codeService->generateCode('cash_receipt', $companyId);
            } else {
                $previewCode = $codeService->previewNextCode('cash_receipt', $companyId);
                if ($previewCode && $receiptNumber === $previewCode) {
                    $receiptNumber = $codeService->generateCode('cash_receipt', $companyId);
                }
            }
            
            $referenceNo = $data['reference_no'] ?? null;

            $receipt = CashReceipt::create([
                'date' => $data['date'],
                'reference_no' => $referenceNo,
                'receipt_number' => $receiptNumber,
                'description' => $data['description'] ?? null,
                'total' => $totalAmount,
                'status' => $isPosted ? 'posted' : 'draft',
                'sub_module' => 'penerimaan_uang_muka',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'cash_bank_transaction_id' => $cashBankTx->id,
                'recipient_id' => $data['recipient_id'] ?? null,
                'to_account_id' => $data['to_account_id'] ?? null,
                'company_id' => $data['company_id'] ?? $bankAccount->company_id,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            if ($isPosted) {
                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $data['reference_no'] ?? null,
                    'description' => $data['description'] ?? null,
                    'amount' => $totalAmount,
                    'status' => 'posted',
                    'sub_module' => 'penerimaan_uang_muka',
                    'reference_type' => CashReceipt::class,
                    'reference_id' => $receipt->id,
                    'cash_bank_transaction_id' => $cashBankTx->id,
                    'company_id' => $data['company_id'] ?? $bankAccount->company_id,
                    'department_id' => $data['department_id'] ?? throw new InvalidArgumentException('department_id is required'),
                    'cost_center_id' => $data['cost_center_id'] ?? throw new InvalidArgumentException('cost_center_id is required'),
                ], [
                [
                    'account_id' => $data['to_account_id'] ?? $bankAccount->account_id,
                    'debit' => $totalAmount,
                    'credit' => 0,
                    'notes' => 'Cash/Bank',
                    'cost_center_id' => $data['cost_center_id'] ?? null,
                ],
                ...collect($items)->map(fn($item) => [
                    'account_id' => $item['account_id'] ?? null,
                    'debit' => 0,
                    'credit' => $item['amount'] ?? $item['total'] ?? 0,
                    'notes' => $item['description'] ?? 'Uang Muka Diterima',
                    'cost_center_id' => $item['cost_center_id'] ?? ($data['cost_center_id'] ?? null),
                ])->all(),
                ]);
            }

            collect($items)->each(function ($item) use ($receipt) {
                CashReceiptItem::create([
                    'cash_receipt_id' => $receipt->id,
                    'account_id' => $item['account_id'],
                    'amount' => $item['amount'] ?? $item['total'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            });

            return $receipt;
        });
    }

    public function createCashOut(array $data): CashDisbursement
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            $this->assertHasItems($items);

            $totalAmount = $this->calculateTotal($items);
            $this->assertPositiveAmount($totalAmount);
            
            $glAccountId = $data['from_account_id'] ?? null;
            if (!$glAccountId) {
                throw new InvalidArgumentException('from_account_id (GL Account) is required');
            }
            
            $glAccount = Account::findOrFail($glAccountId);
            if (!$glAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }
            
            // $bankAccount = BankAccount::where('account_id', $glAccountId)->first();
            // if (!$bankAccount) {
            //     throw new InvalidArgumentException('Bank Account tidak ditemukan untuk GL Account ini. Silakan buat Bank Account terlebih dahulu dan hubungkan ke GL Account ini.');
            // }
            
            // $this->ensureBankAccountHasLedgerAccount($bankAccount);
            
            $disbursementStatus = $data['status'] ?? 'draft';
            $isPosted = $this->isPosted($disbursementStatus);
            
            $cashBankTxStatus = $isPosted ? 'posted' : 'draft';

            $cashBankTx = $this->createCashBankTransaction([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'amount' => $totalAmount,
                'sub_module' => 'pengeluaran',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'from_account_id' => $data['from_account_id'] ?? null,
                'to_account_id' => null,
                'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                'status' => $cashBankTxStatus,
            ]);

            $disbursementNumber = $data['disbursement_number'] ?? null;
            $codeService = app(CodeGeneratorService::class);
            $companyId = $data['company_id'] ?? $glAccount->company_id;
            if (empty($disbursementNumber)) {
                $disbursementNumber = $codeService->generateCode('cash_disbursement', $companyId);
            } else {
                $previewCode = $codeService->previewNextCode('cash_disbursement', $companyId);
                if ($previewCode && $disbursementNumber === $previewCode) {
                    $disbursementNumber = $codeService->generateCode('cash_disbursement', $companyId);
                }
            }

            $disbursement = CashDisbursement::create([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'total' => $totalAmount,
                'status' => $disbursementStatus,
                'sub_module' => 'pengeluaran',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'cash_bank_transaction_id' => $cashBankTx->id,
                'recipient_id' => $data['recipient_id'] ?? null,
                'from_account_id' => $glAccountId,
                'disbursement_number' => $disbursementNumber,
                'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            // Hanya buat journal entry jika status posted
            if ($isPosted) {
                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $data['reference_no'] ?? $disbursementNumber ?? null,
                    'description' => $data['description'] ?? null,
                    'amount' => $totalAmount,
                    'status' => 'posted',
                    'sub_module' => 'pengeluaran',
                    'reference_type' => CashDisbursement::class,
                    'reference_id' => $disbursement->id,
                    'cash_bank_transaction_id' => $cashBankTx->id,
                    'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                    'department_id' => $data['department_id'] ?? throw new InvalidArgumentException('department_id is required'),
                    'cost_center_id' => $data['cost_center_id'] ?? throw new InvalidArgumentException('cost_center_id is required'),
                ], [
                ...collect($items)->map(fn($item) => [
                    'account_id' => $item['account_id'] ?? null,
                    'debit' => $item['amount'] ?? $item['total'] ?? 0,
                    'credit' => 0,
                    'notes' => $item['description'] ?? 'Biaya/Aset',
                    'cost_center_id' => $item['cost_center_id'] ?? ($data['cost_center_id'] ?? null),
                ])->all(),
                [
                    'account_id' => $glAccountId,
                    'debit' => 0,
                    'credit' => $totalAmount,
                    'notes' => 'Kas/Bank',
                    'cost_center_id' => $data['cost_center_id'] ?? null,
                ],
                ]);
            }

            collect($items)->each(function ($item) use ($disbursement) {
                CashDisbursementItem::create([
                    'cash_disbursement_id' => $disbursement->id,
                    'account_id' => $item['account_id'],
                    'amount' => $item['amount'] ?? $item['total'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            });

            return $disbursement;
        });
    }

    public function createCashIn(array $data): CashReceipt
    {
        return DB::transaction(function () use ($data) {


            $items = $data['items'] ?? [];
            $this->assertHasItems($items);

            $totalAmount = $this->calculateTotal($items);
            $this->assertPositiveAmount($totalAmount);
            
            $glAccountId = $data['to_account_id'] ?? null;
            if (!$glAccountId) {
                throw new InvalidArgumentException('to_account_id (GL Account) is required');
            }
            
            $glAccount = Account::findOrFail($glAccountId);
            if (!$glAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }

            // $bankAccount = BankAccount::where('account_id', $glAccountId)->first();
            // if (!$bankAccount) {
            //     throw new InvalidArgumentException('Bank Account tidak ditemukan untuk GL Account ini. Silakan buat Bank Account terlebih dahulu dan hubungkan ke GL Account ini.');
            // }
            
            // $this->ensureBankAccountHasLedgerAccount($bankAccount);

            
            $receiptStatus = $data['status'] ?? 'draft';
            $isPosted = $this->isPosted($receiptStatus);
            
            $cashBankTxStatus = $isPosted ? 'posted' : 'draft';

            $cashBankTx = $this->createCashBankTransaction([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'amount' => $totalAmount,
                'sub_module' => 'pemasukan_kas',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'from_account_id' => null,
                'to_account_id' => $glAccountId,
                'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                'status' => $cashBankTxStatus,
            ]);

            $receiptNumber = $data['receipt_number'] ?? null;
            $codeService = app(CodeGeneratorService::class);
            $companyId = $data['company_id'] ?? $glAccount->company_id;
            if (empty($receiptNumber)) {
                $receiptNumber = $codeService->generateCode('cash_receipt', $companyId);
            } else {
                $previewCode = $codeService->previewNextCode('cash_receipt', $companyId);
                if ($previewCode && $receiptNumber === $previewCode) {
                    $receiptNumber = $codeService->generateCode('cash_receipt', $companyId);
                }
            }
            
            $referenceNo = $data['reference_no'] ?? null;

            $receipt = CashReceipt::create([
                'date' => $data['date'],
                'reference_no' => $referenceNo,
                'receipt_number' => $receiptNumber,
                'description' => $data['description'] ?? null,
                'total' => $totalAmount,
                'status' => $receiptStatus,
                'sub_module' => 'pemasukan_kas',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'cash_bank_transaction_id' => $cashBankTx->id,
                'recipient_id' => $data['recipient_id'] ?? null,
                'to_account_id' => $glAccountId,
                'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            // Hanya buat journal entry jika status posted
            if ($isPosted) {
                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $data['reference_no'] ?? $receiptNumber ?? null,
                    'description' => $data['description'] ?? null,
                    'amount' => $totalAmount,
                    'status' => 'posted',
                    'sub_module' => 'pemasukan_kas',
                    'reference_type' => CashReceipt::class,
                    'reference_id' => $receipt->id,
                    'cash_bank_transaction_id' => $cashBankTx->id,
                    'company_id' => $data['company_id'] ?? $glAccount->company_id ?? null,
                    'department_id' => $data['department_id'] ?? throw new InvalidArgumentException('department_id is required'),
                    'cost_center_id' => $data['cost_center_id'] ?? throw new InvalidArgumentException('cost_center_id is required'),
                ], [
                [
                    'account_id' => $glAccountId,
                    'debit' => $totalAmount,
                    'credit' => 0,
                    'notes' => 'Kas/Bank',
                    'cost_center_id' => $data['cost_center_id'] ?? null,
                ],
                ...collect($items)->map(fn($item) => [
                    'account_id' => $item['account_id'] ?? null,
                    'debit' => 0,
                    'credit' => $item['amount'] ?? $item['total'] ?? 0,
                    'notes' => $item['description'] ?? 'Akun Lawan',
                    'cost_center_id' => $item['cost_center_id'] ?? ($data['cost_center_id'] ?? null),
                ])->all(),
                ]);
            }

            collect($items)->each(function ($item) use ($receipt) {
                CashReceiptItem::create([
                    'cash_receipt_id' => $receipt->id,
                    'account_id' => $item['account_id'],
                    'amount' => $item['amount'] ?? $item['total'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            });

            return $receipt;
        });
    }

    public function createCashTransfer(array $data): CashTransfer
    {
        return DB::transaction(function () use ($data) {
            $this->assertPositiveAmount($data['amount'] ?? null);
            
            $fromAccountId = $data['from_account_id'] ?? null;
            $toAccountId = $data['to_account_id'] ?? null;
            
            if (!$fromAccountId) {
                throw new InvalidArgumentException('from_account_id is required');
            }
            
            if (!$toAccountId) {
                throw new InvalidArgumentException('to_account_id is required');
            }
            
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);
            
            if (!$fromAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }
            
            if (!$toAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }
            
            $isPosted = $this->isPosted($data['status'] ?? null);

            if ($fromAccount->id === $toAccount->id) {
                throw new InvalidArgumentException('Rekening asal dan tujuan tidak boleh sama.');
            }

            $transferNumber = $data['transfer_number'] ?? null;
            $codeService = app(CodeGeneratorService::class);
            $companyId = $data['company_id'] ?? $fromAccount->company_id;
            if (empty($transferNumber)) {
                $transferNumber = $codeService->generateCode('cash_transfer', $companyId);
            } else {
                $previewCode = $codeService->previewNextCode('cash_transfer', $companyId);
                if ($previewCode && $transferNumber === $previewCode) {
                    $transferNumber = $codeService->generateCode('cash_transfer', $companyId);
                }
            }

            $referenceNo = $data['reference_no'] ?? null;

            $cashBankTx = $this->createCashBankTransaction([
                'date' => $data['date'],
                'reference_no' => $referenceNo,
                'description' => $data['description'] ?? null,
                'amount' => $data['amount'],
                'sub_module' => 'transfer_kas',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'company_id' => $data['company_id'] ?? $fromAccount->company_id,
                'status' => $isPosted ? 'posted' : 'draft',
            ]);

            $transfer = CashTransfer::create([
                'date' => $data['date'],
                'reference_no' => $referenceNo,
                'transfer_number' => $transferNumber,
                'description' => $data['description'] ?? null,
                'amount' => $data['amount'],
                'status' => $isPosted ? 'posted' : 'draft',
                'sub_module' => 'transfer_kas',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'cash_bank_transaction_id' => $cashBankTx->id,
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'company_id' => $data['company_id'] ?? $fromAccount->company_id,
                'created_by_user_id' => Auth::id(),
            ]);

            if ($isPosted) {
                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $referenceNo ?? $transferNumber ?? null,
                    'description' => $data['description'] ?? null,
                    'amount' => $data['amount'],
                    'status' => 'posted',
                    'sub_module' => 'transfer_kas',
                    'reference_type' => CashTransfer::class,
                    'reference_id' => $transfer->id,
                    'cash_bank_transaction_id' => $cashBankTx->id,
                    'company_id' => $data['company_id'] ?? $fromAccount->company_id,
                    'department_id' => $data['department_id'] ?? throw new InvalidArgumentException('department_id is required'),
                    'cost_center_id' => $data['cost_center_id'] ?? throw new InvalidArgumentException('cost_center_id is required'),
                ], [
                    [
                        'account_id' => $toAccount->id,
                        'debit' => $data['amount'],
                        'credit' => 0,
                        'notes' => 'Rekening Tujuan',
                        'cost_center_id' => $data['cost_center_id'] ?? null,
                    ],
                    [
                        'account_id' => $fromAccount->id,
                        'debit' => 0,
                        'credit' => $data['amount'],
                        'notes' => 'Rekening Asal',
                        'cost_center_id' => $data['cost_center_id'] ?? null,
                    ],
                ]);
            }

            return $transfer;
        });
    }

    private function createCashBankTransaction(array $payload): CashBankTransaction
    {
        $userId = Auth::id();

        return CashBankTransaction::create([
            'date' => $payload['date'],
            'reference_no' => $payload['reference_no'] ?? null,
            'description' => $payload['description'] ?? null,
            'amount' => $payload['amount'],
            'sub_module' => $payload['sub_module'],
            'reference_type' => $payload['reference_type'] ?? null,
            'reference_id' => $payload['reference_id'] ?? null,
            'from_account_id' => $payload['from_account_id'] ?? null,
            'to_account_id' => $payload['to_account_id'] ?? null,
            'status' => $payload['status'] ?? 'draft',
            'company_id' => $payload['company_id'] ?? null,
            'created_by_user_id' => $userId,
            'posted_by_user_id' => ($payload['status'] ?? 'draft') === 'posted' ? $userId : null,
            'posted_at' => ($payload['status'] ?? 'draft') === 'posted' ? now() : null,
        ]);
    }

    private function createJournalEntryWithItems(array $header, array $items): JournalEntry
    {
        $userId = Auth::id();

        $entryNumber = $header['reference_no'] ?? null;
        if (empty($entryNumber)) {
            $codeService = app(CodeGeneratorService::class);
            $companyId = $header['company_id'] ?? null;
            $entryNumber = $codeService->generateCode('journal_entry', $companyId);
        }

        $entry = JournalEntry::create([
            'entry_number' => $entryNumber,
            'date' => $header['date'],
            'reference_no' => $header['reference_no'] ?? null,
            'description' => $header['description'] ?? null,
            'amount' => $header['amount'],
            'total_amount' => $header['amount'],
            'status' => $header['status'],
            'is_posted' => $header['status'] === 'posted',
            'sub_module' => $header['sub_module'] ?? null,
            'reference_type' => $header['reference_type'] ?? null,
            'reference_id' => $header['reference_id'] ?? null,
            'cash_bank_transaction_id' => $header['cash_bank_transaction_id'] ?? null,
            'department_id' => $header['department_id'],
            'posted_by_user_id' => $userId,
            'posted_at' => $header['status'] === 'posted' ? now() : null,
            'company_id' => $header['company_id'],
            'created_by_user_id' => $userId,
            'updated_by_user_id' => $userId,
        ]);

        foreach ($items as $item) {
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $item['account_id'],
                'debit' => $item['debit'],
                'credit' => $item['credit'],
                'notes' => $item['notes'] ?? null,
                'cost_center_id' => $item['cost_center_id'] ?? ($header['cost_center_id'] ?? throw new InvalidArgumentException('cost_center_id is required')),
            ]);
        }

        return $entry;
    }

    private function resolveBankAccount(array $data): BankAccount
    {
        $bankAccountId = $data['bank_account_id'] ?? null;
        $accountId = $data['account_id'] ?? $data['to_account_id'] ?? null;

        if ($bankAccountId) {
            return $this->getBankAccount($bankAccountId);
        }

        if ($accountId) {
            $ba = BankAccount::where('account_id', $accountId)->first();
            if ($ba) {
                return $ba;
            }
            throw new InvalidArgumentException('Bank account tidak ditemukan untuk account ini.');
        }

        throw new InvalidArgumentException('bank_account_id atau account_id harus diisi.');
    }

    private function getBankAccount(?int $bankAccountId): BankAccount
    {
        if (!$bankAccountId) {
            throw new InvalidArgumentException('bank_account_id is required');
        }

        return BankAccount::findOrFail($bankAccountId);
    }

    private function ensureBankAccountHasLedgerAccount(BankAccount $bankAccount): void
    {
        
        if (!$bankAccount->account_id) {
            
            throw new InvalidArgumentException('Bank account belum dipetakan ke akun buku besar.');
        }
    }

    private function assertPositiveAmount($amount): void
    {
        if (!$amount || $amount <= 0) {
            throw new InvalidArgumentException('Nominal harus lebih besar dari 0.');
        }
    }

    private function assertHasItems(array $items): void
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Minimal satu item transaksi diperlukan.');
        }
    }

    private function calculateTotal(array $items): float
    {
        return collect($items)->sum(fn($item) => (float) ($item['total'] ?? $item['amount'] ?? 0));
    }

    private function isPosted(?string $status): bool
    {
        return $status === 'posted';
    }

    public function updateJournalEntryOnPost($referenceType, $referenceId, $status): void
    {
        $isPosted = $status === 'posted';
        
        $journalEntry = JournalEntry::where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->first();

        if ($journalEntry) {
            $journalEntry->update([
                'status' => $isPosted ? 'posted' : 'approved',
                'is_posted' => $isPosted,
                'posted_at' => $isPosted ? now() : null,
                'posted_by_user_id' => $isPosted ? Auth::id() : $journalEntry->posted_by_user_id,
                'updated_by_user_id' => Auth::id(),
            ]);

            if ($journalEntry->cash_bank_transaction_id) {
                CashBankTransaction::where('id', $journalEntry->cash_bank_transaction_id)
                    ->update([
                        'status' => $isPosted ? 'posted' : 'approved',
                        'posted_by_user_id' => $isPosted ? Auth::id() : null,
                        'posted_at' => $isPosted ? now() : null,
                    ]);
            }
        }
    }


    public function createJournalEntryForRecord($record): void
    {
        $isPosted = $record->status === 'posted';
        
        $existingJournalEntry = JournalEntry::where('reference_type', get_class($record))
            ->where('reference_id', $record->id)
            ->first();
        
        if (!$isPosted) {
            if ($existingJournalEntry) {
                $existingJournalEntry->items()->delete();
                $existingJournalEntry->delete();
            }
            return;
        }

        $departmentId = $existingJournalEntry->department_id ?? 1; 
        $costCenterId = 1;
        if ($existingJournalEntry) {
            $firstItem = $existingJournalEntry->items()->first();
            if ($firstItem) {
                $costCenterId = $firstItem->cost_center_id ?? 1;
            }
            $existingJournalEntry->items()->delete();
            $existingJournalEntry->delete();
        }

        if ($record instanceof CashReceipt) {
            $this->createJournalEntryForCashReceipt($record, $departmentId, $costCenterId);
        } elseif ($record instanceof CashDisbursement) {
            $this->createJournalEntryForCashDisbursement($record, $departmentId, $costCenterId);
        } elseif ($record instanceof CashTransfer) {
            $this->createJournalEntryForCashTransfer($record, $departmentId, $costCenterId);
        }
    }

    private function createJournalEntryForCashReceipt(CashReceipt $receipt, int $departmentId, int $costCenterId): void
    {
        $items = $receipt->items()->get();
        if ($items->isEmpty()) {
            return;
        }

        $totalAmount = $receipt->total ?? 0;
        $toAccountId = $receipt->to_account_id;
        
        if (!$toAccountId) {
            throw new InvalidArgumentException('to_account_id is required for Cash Receipt');
        }

        $journalItems = [
            [
                'account_id' => $toAccountId,
                'debit' => $totalAmount,
                'credit' => 0,
                'notes' => 'Kas/Bank',
                'cost_center_id' => $costCenterId,
            ],
            ...$items->map(fn($item) => [
                'account_id' => $item->account_id,
                'debit' => 0,
                'credit' => $item->amount ?? 0,
                'notes' => $item->description ?? 'Akun Lawan',
                'cost_center_id' => $costCenterId,
            ])->all(),
        ];

        $this->createJournalEntryWithItems([
            'date' => $receipt->date,
            'reference_no' => $receipt->reference_no ?: $receipt->receipt_number,
            'description' => $receipt->description,
            'amount' => $totalAmount,
            'status' => 'posted',
            'sub_module' => $receipt->sub_module ?? 'pemasukan_kas',
            'reference_type' => CashReceipt::class,
            'reference_id' => $receipt->id,
            'cash_bank_transaction_id' => $receipt->cash_bank_transaction_id,
            'company_id' => $receipt->company_id,
            'department_id' => $departmentId,
            'cost_center_id' => $costCenterId,
        ], $journalItems);
    }

    private function createJournalEntryForCashDisbursement(CashDisbursement $disbursement, int $departmentId, int $costCenterId): void
    {
        $items = $disbursement->items()->get();
        if ($items->isEmpty()) {
            return;
        }

        $totalAmount = $disbursement->total ?? 0;
        $fromAccountId = $disbursement->from_account_id;
        
        if (!$fromAccountId) {
            throw new InvalidArgumentException('from_account_id is required for Cash Disbursement');
        }

        $journalItems = [
            ...$items->map(fn($item) => [
                'account_id' => $item->account_id,
                'debit' => $item->amount ?? 0,
                'credit' => 0,
                'notes' => $item->description ?? 'Biaya/Aset',
                'cost_center_id' => $costCenterId,
            ])->all(),
            [
                'account_id' => $fromAccountId,
                'debit' => 0,
                'credit' => $totalAmount,
                'notes' => 'Kas/Bank',
                'cost_center_id' => $costCenterId,
            ],
        ];

        $this->createJournalEntryWithItems([
            'date' => $disbursement->date,
            'reference_no' => $disbursement->reference_no ?: $disbursement->disbursement_number,
            'description' => $disbursement->description,
            'amount' => $totalAmount,
            'status' => 'posted',
            'sub_module' => $disbursement->sub_module ?? 'pengeluaran',
            'reference_type' => CashDisbursement::class,
            'reference_id' => $disbursement->id,
            'cash_bank_transaction_id' => $disbursement->cash_bank_transaction_id,
            'company_id' => $disbursement->company_id,
            'department_id' => $departmentId,
            'cost_center_id' => $costCenterId,
        ], $journalItems);
    }

    private function createJournalEntryForCashTransfer(CashTransfer $transfer, int $departmentId, int $costCenterId): void
    {
        $fromAccountId = $transfer->from_account_id;
        $toAccountId = $transfer->to_account_id;
        $amount = $transfer->amount ?? 0;
        
        if (!$fromAccountId || !$toAccountId) {
            throw new InvalidArgumentException('from_account_id and to_account_id are required for Cash Transfer');
        }

        $journalItems = [
            [
                'account_id' => $toAccountId,
                'debit' => $amount,
                'credit' => 0,
                'notes' => 'Rekening Tujuan',
                'cost_center_id' => $costCenterId,
            ],
            [
                'account_id' => $fromAccountId,
                'debit' => 0,
                'credit' => $amount,
                'notes' => 'Rekening Asal',
                'cost_center_id' => $costCenterId,
            ],
        ];

        $this->createJournalEntryWithItems([
            'date' => $transfer->date,
            'reference_no' => $transfer->reference_no ?: $transfer->transfer_number,
            'description' => $transfer->description,
            'amount' => $amount,
            'status' => 'posted',
            'sub_module' => $transfer->sub_module ?? 'transfer_kas',
            'reference_type' => CashTransfer::class,
            'reference_id' => $transfer->id,
            'cash_bank_transaction_id' => $transfer->cash_bank_transaction_id,
            'company_id' => $transfer->company_id,
            'department_id' => $departmentId,
            'cost_center_id' => $costCenterId,
        ], $journalItems);
    }

    public function createAdvanceReceiptWithJournal(array $data): AdvanceReceipt
    {
        return DB::transaction(function () use ($data) {
            $items = $data['advanceReceiptItems'] ?? [];
            if (empty($items)) {
                throw new InvalidArgumentException('Minimal satu item diperlukan.');
            }

            $totalAmount = 0;
            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $totalAmount += is_numeric($amount) ? (float) $amount : 0;
            }

            if ($totalAmount <= 0) {
                throw new InvalidArgumentException('Total amount harus lebih dari 0.');
            }

            $toAccount = Account::find($data['to_account_id']);
            if (!$toAccount || !$toAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }

            $isPosted = $this->isPosted($data['status'] ?? null);
            
            $companyId = $data['company_id'] ?? null;
            if (!$companyId) {
                $selectedCompanyId = session('selected_company_id');
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $companyId = $selectedCompanyId;
                } else {
                    $user = Auth::user();
                    if ($user) {
                        $firstCompany = $user->companies()->first();
                        if ($firstCompany) {
                            $companyId = $firstCompany->id;
                        }
                    }
                }
            }
            
            if (!$companyId) {
                throw new InvalidArgumentException('Company ID harus tersedia. Pastikan company dipilih di session atau form.');
            }

            $advanceReceipt = AdvanceReceipt::create([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? $data['advance_receipt_number'] ?? 'AR-' . date('YmdHis'),
                'description' => $data['description'] ?? null,
                'total' => (string) $totalAmount,
                'status' => $data['status'] ?? 'draft',
                'recipient_id' => $data['recipient_id'],
                'to_account_id' => $data['to_account_id'],
                'company_id' => $companyId,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $amount = is_numeric($amount) ? (string) $amount : '0';

                AdvanceReceiptItem::create([
                    'advance_receipt_id' => $advanceReceipt->id,
                    'transaction_classification_id' => $item['transaction_classification_id'],
                    'amount' => $amount,
                    'description' => $item['description'] ?? null,
                ]);
            }

            if ($isPosted) {
                $journalItems = [
                    [
                        'account_id' => $data['to_account_id'],
                        'debit' => $totalAmount,
                        'credit' => 0,
                        'notes' => 'Cash/Bank Account',
                        'cost_center_id' => $data['cost_center_id'] ?? null,
                    ],
                ];

                foreach ($items as $index => $item) {
                    $amount = $item['amount'] ?? 0;
                    if (is_string($amount)) {
                        $amount = str_replace(['.', ','], ['', '.'], $amount);
                    }
                    $amount = is_numeric($amount) ? (float) $amount : 0;

                    $classification = \App\Models\TransactionClassification::find($item['transaction_classification_id']);
                    if (!$classification) {
                        throw new InvalidArgumentException("Transaction Classification pada item #" . ($index + 1) . " tidak ditemukan.");
                    }
                    
                    if (!$classification->default_account_id) {
                        throw new InvalidArgumentException("Transaction Classification '{$classification->name}' pada item #" . ($index + 1) . " tidak memiliki default account. Silakan set default account di Transaction Classification.");
                    }

                    $journalItems[] = [
                        'account_id' => $classification->default_account_id,
                        'debit' => 0,
                        'credit' => $amount,
                        'notes' => $item['description'] ?? $classification->name,
                        'cost_center_id' => $data['cost_center_id'] ?? null,
                    ];
                }

                $departmentId = $data['department_id'] ?? \App\Models\Department::first()?->id;
                $costCenterId = $data['cost_center_id'] ?? \App\Models\CostCenter::first()?->id;

                if (!$departmentId || !$costCenterId) {
                    throw new InvalidArgumentException('Department dan Cost Center harus tersedia untuk membuat journal entry.');
                }

                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $advanceReceipt->reference_no,
                    'description' => $data['description'] ?? 'Advance Receipt',
                    'amount' => $totalAmount,
                    'status' => 'posted',
                    'sub_module' => 'penerimaan_uang_muka',
                    'reference_type' => AdvanceReceipt::class,
                    'reference_id' => $advanceReceipt->id,
                    'cash_bank_transaction_id' => null,
                    'company_id' => $companyId,
                    'department_id' => $departmentId,
                    'cost_center_id' => $costCenterId,
                ], $journalItems);
            }

            return $advanceReceipt;
        });
    }

    public function createAdvanceDisbursementWithJournal(array $data): AdvanceDisbursement
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            if (empty($items)) {
                throw new InvalidArgumentException('Minimal satu item diperlukan.');
            }

            $totalAmount = 0;
            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $totalAmount += is_numeric($amount) ? (float) $amount : 0;
            }

            if ($totalAmount <= 0) {
                throw new InvalidArgumentException('Total amount harus lebih dari 0.');
            }

            $fromAccount = Account::find($data['from_account_id']);
            if (!$fromAccount || !$fromAccount->is_cash_bank) {
                throw new InvalidArgumentException('Account yang dipilih harus memiliki flag is_cash_bank = true');
            }

            $isPosted = $this->isPosted($data['status'] ?? null);
            
            $companyId = $data['company_id'] ?? null;
            if (!$companyId) {
                $selectedCompanyId = session('selected_company_id');
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $companyId = $selectedCompanyId;
                } else {
                    $user = Auth::user();
                    if ($user) {
                        $firstCompany = $user->companies()->first();
                        if ($firstCompany) {
                            $companyId = $firstCompany->id;
                        }
                    }
                }
            }
            
            if (!$companyId) {
                throw new InvalidArgumentException('Company ID harus tersedia. Pastikan company dipilih di session atau form.');
            }

            $referenceNo = $data['reference_no'] ?? null;
            if (empty($referenceNo) && !empty($data['advance_number'])) {
                $referenceNo = $data['advance_number'];
            }
            if (empty($referenceNo)) {
                $referenceNo = 'AD-' . date('YmdHis');
            }

            $advanceDisbursement = AdvanceDisbursement::create([
                'date' => $data['date'],
                'reference_no' => $referenceNo,
                'advance_number' => $data['advance_number'] ?? null,
                'description' => $data['description'] ?? null,
                'total' => (string) $totalAmount,
                'status' => $data['status'] ?? 'draft',
                'recipient_id' => $data['recipient_id'],
                'from_account_id' => $data['from_account_id'],
                'company_id' => $companyId,
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $amount = is_numeric($amount) ? (string) $amount : '0';

                AdvanceDisbursementItem::create([
                    'advance_disbursement_id' => $advanceDisbursement->id,
                    'transaction_classification_id' => $item['transaction_classification_id'],
                    'amount' => $amount,
                    'description' => $item['description'] ?? null,
                ]);
            }

            if ($isPosted) {
                $journalItems = [];

                foreach ($items as $index => $item) {
                    $amount = $item['amount'] ?? 0;
                    if (is_string($amount)) {
                        $amount = str_replace(['.', ','], ['', '.'], $amount);
                    }
                    $amount = is_numeric($amount) ? (float) $amount : 0;

                    $classification = \App\Models\TransactionClassification::find($item['transaction_classification_id']);
                    if (!$classification) {
                        throw new InvalidArgumentException("Transaction Classification pada item #" . ($index + 1) . " tidak ditemukan.");
                    }
                    
                    if (!$classification->default_account_id) {
                        throw new InvalidArgumentException("Transaction Classification '{$classification->name}' pada item #" . ($index + 1) . " tidak memiliki default account. Silakan set default account di Transaction Classification.");
                    }

                    $journalItems[] = [
                        'account_id' => $classification->default_account_id,
                        'debit' => $amount,
                        'credit' => 0,
                        'notes' => $item['description'] ?? $classification->name,
                        'cost_center_id' => $data['cost_center_id'] ?? null,
                    ];
                }

                $journalItems[] = [
                    'account_id' => $data['from_account_id'],
                    'debit' => 0,
                    'credit' => $totalAmount,
                    'notes' => 'Cash/Bank Account',
                    'cost_center_id' => $data['cost_center_id'] ?? null,
                ];

                $departmentId = $data['department_id'] ?? \App\Models\Department::first()?->id;
                $costCenterId = $data['cost_center_id'] ?? \App\Models\CostCenter::first()?->id;

                if (!$departmentId || !$costCenterId) {
                    throw new InvalidArgumentException('Department dan Cost Center harus tersedia untuk membuat journal entry.');
                }

                $this->createJournalEntryWithItems([
                    'date' => $data['date'],
                    'reference_no' => $advanceDisbursement->reference_no,
                    'description' => $data['description'] ?? 'Advance Disbursement',
                    'amount' => $totalAmount,
                    'status' => 'posted',
                    'sub_module' => 'pengeluaran_uang_muka',
                    'reference_type' => AdvanceDisbursement::class,
                    'reference_id' => $advanceDisbursement->id,
                    'cash_bank_transaction_id' => null,
                    'company_id' => $companyId,
                    'department_id' => $departmentId,
                    'cost_center_id' => $costCenterId,
                ], $journalItems);
            }

            return $advanceDisbursement;
        });
    }
}

