<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashBankTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no',
        'description',
        'amount',
        'sub_module',
        'reference_type',
        'reference_id',
        'from_account_id',
        'to_account_id',
        'status',
        'company_id',
        'created_by_user_id',
        'posted_by_user_id',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'date' => 'date',
            'amount' => 'decimal:2',
            'from_account_id' => 'integer',
            'to_account_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
            'posted_by_user_id' => 'integer',
            'posted_at' => 'datetime',
        ];
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'to_account_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function postedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }
}











