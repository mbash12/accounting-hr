<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceDisbursementItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'description',
        'advance_disbursement_id',
        'transaction_classification_id',
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
            'advance_disbursement_id' => 'integer',
            'transaction_classification_id' => 'integer',
        ];
    }

    public function advanceDisbursement(): BelongsTo
    {
        return $this->belongsTo(AdvanceDisbursement::class);
    }

    public function transactionClassification(): BelongsTo
    {
        return $this->belongsTo(TransactionClassification::class);
    }
}
