<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntryItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'debit',
        'credit',
        'notes',
        'journal_entry_id',
        'account_id',
        'cost_center_id',
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
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
            'journal_entry_id' => 'integer',
            'account_id' => 'integer',
            'cost_center_id' => 'integer',
        ];
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function getContactAttribute(): ?Contact
    {
        $journal = $this->journalEntry;
        if (!$journal || !$journal->reference_type || !$journal->reference_id) {
            return null;
        }

        try {
            $type = $journal->reference_type;

            if ($type === 'App\Services\SalesInvoice') {
                $type = \App\Models\SalesInvoice::class;
            }

            if (!class_exists($type)) {
                return null;
            }

            $source = $type::find($journal->reference_id);
            if (!$source) return null;

            return $source->customer 
                ?? $source->supplier 
                ?? $source->contact 
                ?? $source->employee 
                ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
