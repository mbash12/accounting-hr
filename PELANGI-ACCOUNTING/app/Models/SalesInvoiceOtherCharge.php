<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceOtherCharge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_invoice_id',
        'name',
        'account_id',
        'amount',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'sales_invoice_id' => 'integer',
            'account_id' => 'integer',
            'amount' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
