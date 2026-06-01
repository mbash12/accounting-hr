<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeferredRevenueSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'period_number',
        'period_start',
        'period_end',
        'planned_amount',
        'recognized_amount',
        'recognized_date',
        'status',
        'notes',
        'deferred_revenue_id',
        'journal_entry_id',
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
            'period_start' => 'date',
            'period_end' => 'date',
            'recognized_date' => 'date',
            'planned_amount' => 'decimal:2',
            'recognized_amount' => 'decimal:2',
            'deferred_revenue_id' => 'integer',
            'journal_entry_id' => 'integer',
        ];
    }

    public function deferredRevenue(): BelongsTo
    {
        return $this->belongsTo(DeferredRevenue::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
