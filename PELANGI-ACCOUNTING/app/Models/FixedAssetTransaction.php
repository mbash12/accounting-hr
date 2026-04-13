<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FixedAssetTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by_user_id) {
                $model->created_by_user_id = Auth::id();
            }
        });

        static::saved(function ($model) {
            if ($model->create_journal) {
                $model->createJournalEntry();
            }
        });

        static::deleted(function ($model) {
            $model->deleteJournalEntry();
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
        'journal_value',
        'asset_value',
        'difference',
        'transaction_type',
        'fixed_asset_id',
        'company_id',
        'created_by_user_id',
        'create_journal',
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
            'journal_value' => 'decimal:2',
            'asset_value' => 'decimal:2',
            'difference' => 'decimal:2',
            'fixed_asset_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
            'create_journal' => 'boolean',
        ];
    }

    public function fixedAsset(): BelongsTo
    {
        return $this->belongsTo(FixedAsset::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'reference_id')
            ->where('reference_type', self::class);
    }

    public function createJournalEntry(): void
    {
        $asset = $this->fixedAsset;
        if (!$asset) return;

        $category = $asset->category;
        if (!$category) return;

        $amount = abs((float) $this->journal_value);
        if ($amount <= 0) return;

        // Get accounts from category
        $assetAccountId = $category->asset_account_id;
        $depreciationAccountId = $category->depreciation_account_id;
        $accumulatedDepAccountId = $category->accumulated_depreciation_account_id;
        $salesAccountId = $category->sales_account_id;

        $items = [];

        switch ($this->transaction_type) {
            case 'acquisition':
                // Dr Asset, Cr Cash/Payable (use asset account for now)
                if ($assetAccountId) {
                    $items[] = ['account_id' => $assetAccountId, 'debit' => $amount, 'credit' => 0];
                }
                break;

            case 'depreciation':
                // Dr Depreciation Expense, Cr Accumulated Depreciation
                if ($depreciationAccountId) {
                    $items[] = ['account_id' => $depreciationAccountId, 'debit' => $amount, 'credit' => 0];
                }
                if ($accumulatedDepAccountId) {
                    $items[] = ['account_id' => $accumulatedDepAccountId, 'debit' => 0, 'credit' => $amount];
                }
                break;

            case 'disposal':
                // Dr Accumulated Depreciation, Dr Cash/Loss, Cr Asset, Cr Gain
                if ($accumulatedDepAccountId) {
                    $accDep = (float) $asset->accumulated_depreciation;
                    $items[] = ['account_id' => $accumulatedDepAccountId, 'debit' => $accDep, 'credit' => 0];
                }
                if ($assetAccountId) {
                    $items[] = ['account_id' => $assetAccountId, 'debit' => 0, 'credit' => (float) $asset->acquisition_value];
                }
                if ($salesAccountId && $amount > 0) {
                    $items[] = ['account_id' => $salesAccountId, 'debit' => 0, 'credit' => $amount];
                }
                break;

            case 'revaluation':
            case 'impairment':
                // Dr/Cr Asset based on difference
                $diff = (float) $this->difference;
                if ($assetAccountId && abs($diff) > 0) {
                    $items[] = [
                        'account_id' => $assetAccountId,
                        'debit' => $diff > 0 ? $diff : 0,
                        'credit' => $diff < 0 ? abs($diff) : 0,
                    ];
                }
                break;
        }

        if (empty($items)) return;

        // Create journal entry
        $journalEntry = JournalEntry::create([
            'entry_number' => 'FA-' . now()->format('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT),
            'date' => $this->date,
            'reference_no' => $this->reference_no,
            'description' => $this->description ?: "Fixed Asset {$this->transaction_type}: {$asset->name}",
            'amount' => $amount,
            'total_amount' => $amount,
            'status' => 'posted',
            'is_posted' => true,
            'sub_module' => 'fixed_asset_' . $this->transaction_type,
            'reference_type' => self::class,
            'reference_id' => $this->id,
            'posted_by_user_id' => Auth::id(),
            'posted_at' => now(),
            'company_id' => $this->company_id,
            'created_by_user_id' => Auth::id(),
            'updated_by_user_id' => Auth::id(),
        ]);

        foreach ($items as $item) {
            if ($item['account_id'] && ($item['debit'] > 0 || $item['credit'] > 0)) {
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'],
                    'credit' => $item['credit'],
                    'notes' => $this->description,
                ]);
            }
        }
    }

    public function deleteJournalEntry(): void
    {
        $entries = JournalEntry::where('reference_type', self::class)
            ->where('reference_id', $this->id)
            ->get();

        foreach ($entries as $entry) {
            $entry->items()->delete();
            $entry->delete();
        }
    }
}
