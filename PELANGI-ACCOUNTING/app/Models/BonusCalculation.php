<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BonusCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bonus_calculations';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by_user_id) {
                $model->created_by_user_id = Auth::id();
            }

            if (!$model->company_id) {
                $selectedCompanyId = session('selected_company_id');
                if ($selectedCompanyId) {
                    $model->company_id = $selectedCompanyId;
                }
            }
        });
    }

    protected $fillable = [
        'name',
        'year',
        'payout_date',
        'description',
        'is_taxable',
        'status',
        'total_amount',
        'total_pph21',
        'journal_entry_id',
        'company_id',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'payout_date' => 'date',
            'is_taxable' => 'boolean',
            'total_amount' => 'decimal:2',
            'total_pph21' => 'decimal:2',
            'journal_entry_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(BonusCalculationItem::class, 'bonus_calculation_id');
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
