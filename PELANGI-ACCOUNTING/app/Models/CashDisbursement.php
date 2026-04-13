<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashDisbursement extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($record) {
            $record->journalEntry()->delete();
            
            if ($record->cash_bank_transaction_id) {
                CashBankTransaction::find($record->cash_bank_transaction_id)?->delete();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'reference_no',
        'description',
        'total',
        'status',
        'sub_module',
        'reference_type',
        'reference_id',
        'cash_bank_transaction_id',
        'recipient_id',
        'from_account_id',
        'company_id',
        'created_by_user_id',
        'updated_by_user_id',
        'disbursement_number',
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
            'date' => 'date',
            'reference_id' => 'integer',
            'cash_bank_transaction_id' => 'integer',
            'recipient_id' => 'integer',
            'from_account_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
            'updated_by_user_id' => 'integer',
        ];
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashBankTransaction(): BelongsTo
    {
        return $this->belongsTo(CashBankTransaction::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CashDisbursementItem::class);
    }

    public function journalEntry(): HasOne
    {
        return $this->hasOne(JournalEntry::class, 'reference_id')
            ->where('reference_type', self::class);
    }
}
