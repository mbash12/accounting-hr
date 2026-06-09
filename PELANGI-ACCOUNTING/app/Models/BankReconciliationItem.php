<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankReconciliationItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_reconciliation_id',
        'type',
        'bank_date',
        'bank_description',
        'bank_debit',
        'bank_credit',
        'reference_no',
        'account_code',
        'suggested_invoice_id',
        'suggested_invoice_type',
        'suggested_invoice_amount',
        'match_status',
        'debit',
        'credit',
        'description',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'bank_reconciliation_id' => 'integer',
            'bank_date' => 'date',
            'bank_debit' => 'decimal:2',
            'bank_credit' => 'decimal:2',
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
            'suggested_invoice_id' => 'integer',
            'suggested_invoice_amount' => 'decimal:2',
            'imported_at' => 'datetime',
        ];
    }

    public function bankReconciliation(): BelongsTo
    {
        return $this->belongsTo(BankReconciliation::class);
    }

    public function suggestedInvoice()
    {
        if ($this->suggested_invoice_type === SalesInvoice::class) {
            return $this->belongsTo(SalesInvoice::class, 'suggested_invoice_id');
        }
        if ($this->suggested_invoice_type === PurchaseInvoice::class) {
            return $this->belongsTo(PurchaseInvoice::class, 'suggested_invoice_id');
        }
        return null;
    }
}
