<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountMapping extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Document types that can have account mappings
     */
    const DOCUMENT_TYPES = [
        'sales_order' => 'Sales Order',
        'delivery_document' => 'Sales Delivery',
        'sales_invoice' => 'Sales Invoice',
        'sales_return' => 'Sales Return',
        'purchase_order' => 'Purchase Order',
        'goods_receipt' => 'Goods Receipt',
        'purchase_invoice' => 'Purchase Invoice',
        'purchase_return' => 'Purchase Return',
        'opening_balance' => 'Opening Balance',
        'cash_receipt' => 'Cash Receipt',
        'cash_disbursement' => 'Cash Disbursement',
        'bank_receipt' => 'Bank Receipt',
        'bank_disbursement' => 'Bank Disbursement',
        'advance_receipt' => 'Advance Receipt',
        'advance_disbursement' => 'Advance Disbursement',
        'cash_transfer' => 'Cash Transfer',
        'receivable_payment' => 'Receivable Payment',
        'payable_payment' => 'Payable Payment',
        'payroll' => 'Payroll',
    ];

    /**
     * Mapping types available for each document
     */
    const MAPPING_TYPES = [
        // Sales mappings
        'sales' => 'Sales Revenue',
        'accounts_receivable' => 'Accounts Receivable',
        'discount' => 'Discount Given/Received',
        'tax' => 'Tax Payable/Receivable',
        'other_charges' => 'Other Charges',
        'cogs' => 'Cost of Goods Sold',
        'inventory' => 'Inventory',
        'accounts_payable' => 'Accounts Payable',
        'purchases' => 'Purchases/Expenses',
        'sales_return' => 'Sales Returns',
        'purchase_return' => 'Purchase Returns',
        'advance_receivable' => 'Advance Receivable',
        'advance_payable' => 'Advance Payable',
        'grni' => 'Goods Received Not Invoiced',
        'cash' => 'Cash Account',
        'bank' => 'Bank Account',
        'expense' => 'Expense Account',
        'gain' => 'Gain/Income',
        'loss' => 'Loss/Expense',
        'write_off' => 'Write Off',
        // Payroll mappings
        'salary_expense' => 'Salary Expense',
        'bpjs_expense' => 'BPJS Expense (Employer)',
        'salary_payable' => 'Salary Payable (Net)',
        'pph21_payable' => 'PPh21 Payable',
        'bpjs_payable' => 'BPJS Payable (Total)',
    ];

    /**
     * Mapping types available for each document type
     */
    const DOCUMENT_MAPPING_TYPES = [
        // ... (rest of mappings)
        'sales_order' => ['advance_receivable'],
        'delivery_document' => ['cogs', 'inventory'],
        'sales_invoice' => ['accounts_receivable', 'sales', 'discount', 'tax', 'other_charges'],
        'sales_return' => ['accounts_receivable', 'sales_return'],
        'purchase_order' => ['advance_payable'],
        'goods_receipt' => ['inventory', 'grni'],
        'purchase_invoice' => ['accounts_payable', 'purchases', 'tax', 'discount', 'other_charges'],
        'purchase_return' => ['accounts_payable', 'purchase_return'],
        'cash_receipt' => ['cash', 'accounts_receivable'],
        'cash_disbursement' => ['cash', 'accounts_payable'],
        'bank_receipt' => ['bank', 'accounts_receivable'],
        'bank_disbursement' => ['bank', 'accounts_payable'],
        'advance_receipt' => ['cash', 'advance_receivable'],
        'advance_disbursement' => ['cash', 'advance_payable'],
        'cash_transfer' => ['cash', 'bank'],
        'receivable_payment' => ['other_charges', 'discount', 'write_off'],
        'payable_payment' => ['other_charges', 'discount', 'write_off'],
        'payroll' => ['salary_expense', 'bpjs_expense', 'salary_payable', 'pph21_payable', 'bpjs_payable'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'document_type',
        'mapping_type',
        'account_id',
        'description',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'company_id' => 'integer',
            'account_id' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get mappings for a specific document type
     */
    public static function getMappingsForDocument(string $documentType, int $companyId)
    {
        return self::with('account')
            ->where('company_id', $companyId)
            ->where('document_type', $documentType)
            ->where('is_active', true)
            ->get()
            ->pluck('account', 'mapping_type');
    }

    /**
     * Get a specific account mapping
     */
    public static function getAccountMapping(string $documentType, string $mappingType, ?int $companyId = null): ?Account
    {
        $selectedCompanyId = $companyId ?? session('selected_company_id');
        
        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            return null;
        }
        
        $mapping = self::where('company_id', $selectedCompanyId)
            ->where('document_type', $documentType)
            ->where('mapping_type', $mappingType)
            ->where('is_active', true)
            ->with('account')
            ->first();

        return $mapping?->account;
    }
}
