<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'account_type',
        'classification_type',
        'description',
        'is_header',
        'is_cash_bank',
        'is_active',
        'level',
        'opening_balance',
        'current_balance',
        'parent_id',
        'classification_id',
        'company_id',
        'created_by_user_id',
        'cash_flow',
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
            'is_header' => 'boolean',
            'is_cash_bank' => 'boolean',
            'is_active' => 'boolean',
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'parent_id' => 'integer',
            'classification_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'classification_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id')->with('children')->orderBy('code');
    }

    public function subclassifications(): HasMany
    {
        return $this->hasMany(Account::class, 'classification_id')->orderBy('code');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope to get accounts under a specific parent account (including the parent itself)
     */
    public function scopeUnderParent($query, $parentCode)
    {
        $parentAccount = static::where('code', $parentCode)->first();

        if (!$parentAccount) {
            return $query->whereRaw('1 = 0'); // Return no results if parent doesn't exist
        }

        // Get all descendant IDs using a recursive CTE or simpler approach
        $descendantIds = $this->getAllDescendantIds($parentAccount->id);

        return $query->whereIn('id', $descendantIds);
    }

    /**
     * Get all descendant account IDs (including the parent)
     */
    private function getAllDescendantIds($parentId)
    {
        $ids = [$parentId];
        $processed = [];
        $toProcess = [$parentId];

        while (!empty($toProcess)) {
            $currentId = array_pop($toProcess);

            if (\in_array($currentId, $processed)) {
                continue;
            }

            $processed[] = $currentId;

            // Get direct children
            $children = static::where('parent_id', $currentId)->pluck('id')->toArray();

            foreach ($children as $childId) {
                if (!\in_array($childId, $ids)) {
                    $ids[] = $childId;
                    $toProcess[] = $childId;
                }
            }
        }

        return $ids;
    }
}
