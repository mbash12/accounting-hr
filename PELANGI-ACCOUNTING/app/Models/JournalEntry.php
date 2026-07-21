<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Traits\EnforcesOpenPeriod;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes, EnforcesOpenPeriod;

    public function reference()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::creating(function (JournalEntry $journalEntry) {
            if (Auth::check() && !$journalEntry->created_by_user_id) {
                $journalEntry->created_by_user_id = Auth::id();
            }
        });

        static::updating(function (JournalEntry $journalEntry) {
            if (Auth::check()) {
                $journalEntry->updated_by_user_id = Auth::id();
            }
        });

        static::deleted(function (JournalEntry $journalEntry) {
            $journalEntry->items()->each(function ($item) {
                $item->delete();
            });
        });

        static::restored(function (JournalEntry $journalEntry) {
            $journalEntry->items()->withTrashed()->each(function ($item) {
                $item->restore();
            });
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_number',
        'date',
        'reference_no',
        'description',
        'amount',
        'total_amount',
        'status',
        'is_posted',
        'sub_module',
        'reference_type',
        'reference_id',
        'cash_bank_transaction_id',
        'department_id',
        'posted_by_user_id',
        'posted_at',
        'company_id',
        'created_by_user_id',
        'updated_by_user_id',
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
            'amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'is_posted' => 'boolean',
            'posted_at' => 'datetime',
            'reference_id' => 'integer',
            'cash_bank_transaction_id' => 'integer',
            'department_id' => 'integer',
            'posted_by_user_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
            'updated_by_user_id' => 'integer',
            
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function postedByUser(): BelongsTo
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

    /**
     * Exclude year-end closing journals from operational P&L reports.
     */
    public function scopeExcludePeriodClosing($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('sub_module')
                ->orWhere('sub_module', '!=', 'period_closing');
        });
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        // Journal voucher convention: all debit lines first, then credits (stable by id).
        return $this->hasMany(JournalEntryItem::class)
            ->orderByRaw('CASE WHEN COALESCE(debit, 0) > 0 THEN 0 ELSE 1 END')
            ->orderBy('id');
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }
}
