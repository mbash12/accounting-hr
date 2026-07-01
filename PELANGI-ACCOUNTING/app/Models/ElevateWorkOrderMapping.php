<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElevateWorkOrderMapping extends Model
{
    const STATUS_PENDING           = 'pending';
    const STATUS_CONTACT_RESOLVED  = 'contact_resolved';
    const STATUS_INVOICE_CREATED   = 'invoice_created';
    const STATUS_PAYMENT_CREATED   = 'payment_created';
    const STATUS_COMPLETED         = 'completed';
    const STATUS_FAILED            = 'failed';

    protected $fillable = [
        'work_order_id',
        'work_order_number',
        'company_id',
        'contact_id',
        'sales_invoice_id',
        'receivable_payment_id',
        'status',
        'error_message',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload'               => 'array',
            'company_id'            => 'integer',
            'contact_id'            => 'integer',
            'sales_invoice_id'      => 'integer',
            'receivable_payment_id' => 'integer',
        ];
    }


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function receivablePayment(): BelongsTo
    {
        return $this->belongsTo(ReceivablePayment::class);
    }


    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status'        => self::STATUS_FAILED,
            'error_message' => $message,
        ]);
    }
}
