<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class BankReconciliation extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            try {
                $refPrefix = 'BANK-RECON-' . $model->id;

                // Delete related payments
                \App\Models\ReceivablePayment::where('reference_no', $refPrefix)->delete();
                \App\Models\PayablePayment::where('reference_no', $refPrefix)->delete();

                // Delete related journal entries (entry_number starts with BANK-RECON-{id})
                \App\Models\JournalEntry::where('entry_number', 'LIKE', $refPrefix . '%')
                    ->each(function ($journal) {
                        $journal->items()->delete();
                        $journal->delete();
                    });
            } catch (\Throwable $e) {
                Log::error('Error cleaning up bank reconciliation data: ' . $e->getMessage());
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'statement_date',
        'statement_balance',
        'book_balance',
        'reconciliation_date',
        'status',
        'reconciled_at',
        'difference',
        'bank_account_id',
        'reconciled_by_user_id',
        'company_id',
        'created_by_user_id',
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
            'statement_date' => 'date',
            'reconciliation_date' => 'date',
            'reconciled_at' => 'timestamp',
            'bank_account_id' => 'integer',
            'reconciled_by_user_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BankReconciliationItem::class);
    }

    public function journalEntry(): MorphOne
    {
        return $this->morphOne(JournalEntry::class, 'reference');
    }


    public function reconciledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
