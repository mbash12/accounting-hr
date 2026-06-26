<?php

namespace App\Services;

use App\Models\DocumentNumbering;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CodeGeneratorService
{
    /**
     * Generate a new code based on document type
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return string|null
     */
    public function generateCode(string $documentType, ?int $companyId = null): ?string
    {
        // Get the configuration OUTSIDE the transaction to avoid transaction abort issues
        $cfg = $this->getDocumentNumbering($documentType, $companyId);
        if (!$cfg || !$cfg->is_active) {
            return null;
        }

        return DB::transaction(function () use ($cfg) {
            $documentNumbering = DocumentNumbering::where('id', $cfg->id)->lockForUpdate()->first();
            if (!$documentNumbering) {
                return null;
            }

            $base = $documentNumbering->next_number;
            if ($this->shouldResetNumber($documentNumbering)) {
                $base = 0;
            }
            $next = $base + 1;
            $code = $documentNumbering->formatCode($next);
            $documentNumbering->next_number = $next;
            $documentNumbering->save();

            return $code;
        });
    }

    /**
     * Get document numbering configuration
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return DocumentNumbering|null
     */
    private function getDocumentNumbering(string $documentType, ?int $companyId = null): ?DocumentNumbering
    {
        if ($companyId) {
            $record = DocumentNumbering::where('document_type', $documentType)
                ->where('company_id', $companyId)
                ->first();
            if (!$record) {
                $record = DocumentNumbering::where('document_type', $documentType)
                    ->whereNull('company_id')
                    ->first();
            }
        } else {
            $record = DocumentNumbering::where('document_type', $documentType)
                ->whereNull('company_id')
                ->first();
        }

        if (!$record) {
            $prefixMap = [
                'product' => 'PRD',
                'project' => 'PR',
                'fixed_asset' => 'FA',
                'unit_measurement' => 'UM',
                'bank_account' => 'BA',
                'warehouse' => 'WH',
                'department' => 'DPT',
                'tax' => 'TAX',
                'expedition' => 'EXP',
                'contact' => 'CT',
                'bank' => 'BK',
                'business_type' => 'BT',
                'payment_term' => 'PT',
                'payable_payment' => 'PP',
            ];
            $defaultPrefix = $prefixMap[$documentType] ?? Str::upper(Str::substr($documentType, 0, 2));

            $maxNumber = $this->getMaxNumberFromExistingRecords($documentType, $companyId, $defaultPrefix);

            $record = DocumentNumbering::create([
                'document_type' => $documentType,
                'prefix' => $defaultPrefix,
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
                'next_number' => $maxNumber,
                'reset_period' => 'never',
                'is_active' => true,
                'company_id' => $companyId ?: null,
                'created_by_user_id' => auth()->id(),
            ]);
        } else {
            $maxNumber = $this->getMaxNumberFromExistingRecords($documentType, $companyId, $record->prefix);
            if ($maxNumber > 0 && $record->next_number < $maxNumber) {
                $record->next_number = $maxNumber;
                $record->save();
            }
        }

        return $record;
    }

  
    /**
     * Get max number from existing records in database
     *
     * @param string $documentType
     * @param int|null $companyId
     * @param string $prefix
     * @return int
     */
    private function getMaxNumberFromExistingRecords(string $documentType, ?int $companyId, string $prefix): int
    {
        $modelMap = [
            'cash_receipt' => ['model' => \App\Models\CashReceipt::class, 'field' => 'receipt_number'],
            'cash_disbursement' => ['model' => \App\Models\CashDisbursement::class, 'field' => 'disbursement_number'],
            'purchase_order' => ['model' => \App\Models\PurchaseOrder::class, 'field' => 'purchase_order_no'],
            'sales_order' => ['model' => \App\Models\SalesOrder::class, 'field' => 'sales_order_no'],
            'sales_invoice' => ['model' => \App\Models\SalesInvoice::class, 'field' => 'invoice_number'],
            'purchase_invoice' => ['model' => \App\Models\PurchaseInvoice::class, 'field' => 'invoice_number'],
            'journal_entry' => ['model' => \App\Models\JournalEntry::class, 'field' => 'entry_number'],
            'goods_receipt' => ['model' => \App\Models\GoodsReceipt::class, 'field' => 'receipt_number'],
            'delivery_document' => ['model' => \App\Models\DeliveryDocument::class, 'field' => 'delivery_number'],
            'sales_return' => ['model' => \App\Models\SalesReturn::class, 'field' => 'return_number'],
            'purchase_return' => ['model' => \App\Models\PurchaseReturn::class, 'field' => 'return_number'],
            'receivable_payment' => ['model' => \App\Models\ReceivablePayment::class, 'field' => 'payment_number'],
            'payable_payment' => ['model' => \App\Models\PayablePayment::class, 'field' => 'payment_number'],
            'advance_receipt' => ['model' => \App\Models\AdvanceReceipt::class, 'field' => 'receipt_number'],
            'advance_disbursement' => ['model' => \App\Models\AdvanceDisbursement::class, 'field' => 'disbursement_number'],
            'cash_transfer' => ['model' => \App\Models\CashTransfer::class, 'field' => 'transfer_number'],
            'employee' => ['model' => \App\Models\Employee::class, 'field' => 'employee_id'],
        ];

        if (!isset($modelMap[$documentType])) {
            return 0;
        }

        $modelClass = $modelMap[$documentType]['model'];
        $fieldName = $modelMap[$documentType]['field'];

        if (!class_exists($modelClass)) {
            return 0;
        }

        try {
            $query = $modelClass::withTrashed();
            
            if ($companyId) {
                $query->where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId)
                      ->orWhereNull('company_id');
                });
            }

            $currentYear = now()->format('Y');
            $codes = $query->whereNotNull($fieldName)
                ->where($fieldName, '!=', '')
                ->pluck($fieldName)
                ->filter(function ($code) use ($prefix, $currentYear) {
                    $codeStr = (string) $code;
                    return str_starts_with($codeStr, $prefix);
                })
                ->toArray();

            if (empty($codes)) {
                return 0;
            }

            $numbers = [];
            foreach ($codes as $code) {
                $codeStr = (string) $code;
                
                $codeStr = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $codeStr);
                
                $codeStr = preg_replace('/^\d{4}/', '', $codeStr);
                
                if (preg_match('/\d+/', $codeStr, $matches)) {
                    $number = (int) $matches[0];
                    if ($number > 0) {
                        $numbers[] = $number;
                    }
                }
            }

            return !empty($numbers) ? max($numbers) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Check if number should be reset based on reset period
     *
     * @param DocumentNumbering $documentNumbering
     * @return bool
     */
    private function shouldResetNumber(DocumentNumbering $documentNumbering): bool
    {
        if (!$documentNumbering->reset_period || $documentNumbering->reset_period === 'never') {
            return false;
        }

        $now = now();
        $lastUpdate = $documentNumbering->updated_at ?? $documentNumbering->created_at ?? $now;

        switch ($documentNumbering->reset_period) {
            case 'daily':
                return !$now->isSameDay($lastUpdate);

            case 'weekly':
                return $now->week !== $lastUpdate->week;

            case 'monthly':
                return !$now->isSameMonth($lastUpdate);

            case 'quarterly':
                return $now->quarter !== $lastUpdate->quarter;

            case 'yearly':
                return !$now->isSameYear($lastUpdate);

            default:
                return false;
        }
    }

    /**
     * Generate code for manual entry (doesn't update next number)
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return string|null
     */
    public function generateCodeManual(string $documentType, ?int $companyId = null): ?string
    {
        $documentNumbering = $this->getDocumentNumbering($documentType, $companyId);

        if (!$documentNumbering || !$documentNumbering->is_active) {
            return null;
        }

        $base = $documentNumbering->next_number;
        if ($this->shouldResetNumber($documentNumbering)) {
            $base = 0;
        }
        $next = $base + 1;
        return $documentNumbering->formatCode($next);
    }

    /**
     * Preview next code without updating the counter
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return string|null
     */
    public function previewNextCode(string $documentType, ?int $companyId = null): ?string
    {
        return $this->generateCodeManual($documentType, $companyId);
    }

    /**
     * Reset counter for a specific document type
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return bool
     */
    public function resetCounter(string $documentType, ?int $companyId = null): bool
    {
        $documentNumbering = $this->getDocumentNumbering($documentType, $companyId);

        if (!$documentNumbering) {
            return false;
        }

        return $documentNumbering->update(['next_number' => 1]);
    }

    /**
     * Get current status of document numbering
     *
     * @param string $documentType
     * @param int|null $companyId
     * @return array|null
     */
    public function getDocumentStatus(string $documentType, ?int $companyId = null): ?array
    {
        $documentNumbering = $this->getDocumentNumbering($documentType, $companyId);

        if (!$documentNumbering) {
            return null;
        }

        return [
            'document_type' => $documentNumbering->document_type,
            'prefix' => $documentNumbering->prefix,
            'format' => $documentNumbering->format,
            'next_number' => $documentNumbering->next_number,
            'is_active' => $documentNumbering->is_active,
            'reset_period' => $documentNumbering->reset_period,
            'preview_next_code' => $this->previewNextCode($documentType, $companyId),
        ];
    }
}
