<?php

namespace App\Models;

use App\Traits\HasDependencyValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes, HasDependencyValidation;

    public const OTHER_INCOME_EXPENSE = 'other_income_expense';

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
     * Count of digit characters in an account code (dots/separators ignored).
     * Examples: "10" → 2, "10.01" → 4, "1" → 1
     */
    public static function digitLength(?string $code): int
    {
        return strlen(preg_replace('/\D+/', '', (string) $code) ?? '');
    }

    /**
     * Whether this account is a classification root (top-level: no parent).
     */
    public function isClassificationRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Force header + classification for other_income_expense; leave other types unchanged.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeOtherIncomeExpenseAttributes(array $data): array
    {
        if (($data['account_type'] ?? null) === self::OTHER_INCOME_EXPENSE) {
            $data['is_header'] = true;
            $data['classification_type'] = 'expense';
        }

        return $data;
    }

    /**
     * Validate parent/child rules for other_income_expense headers.
     * Returns an error message, or null when valid.
     */
    public static function validateOtherIncomeExpenseHierarchy(string $accountType, ?self $parent): ?string
    {
        if (! $parent) {
            return null;
        }

        if ($parent->account_type === self::OTHER_INCOME_EXPENSE) {
            if ($accountType === self::OTHER_INCOME_EXPENSE) {
                return __('Other Income/Expense headers cannot be nested.');
            }

            if (! in_array($accountType, ['other_income', 'other_expense'], true)) {
                return __('Only Other Income or Other Expense accounts can be placed under an Other Income/Expense header.');
            }
        }

        return null;
    }

    /**
     * Walk parent chain to the classification root (account with no parent).
     */
    public function classificationRoot(): ?self
    {
        $current = $this;
        $guard = 0;

        while ($current->parent_id !== null && $guard < 50) {
            $parent = static::query()->find($current->parent_id);
            if (!$parent) {
                break;
            }
            $current = $parent;
            $guard++;
        }

        return $current;
    }

    /**
     * Classification root accounts for a company (top-level: parent_id is null).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function classificationRootsForCompany(int $companyId)
    {
        return static::query()
            ->where('company_id', $companyId)
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();
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

    public function getDependencyChecks(): array
    {
        return [
            ['relation' => 'children', 'label' => 'child accounts'],
            ['table' => 'journal_entry_items', 'foreignKey' => 'account_id', 'label' => 'journal entry items'],
            ['table' => 'products', 'foreignKey' => 'tax_id', 'label' => 'products (as tax account)'],
            ['table' => 'taxes', 'foreignKey' => 'purchase_account_id', 'label' => 'taxes (as purchase account)'],
            ['table' => 'taxes', 'foreignKey' => 'sales_account_id', 'label' => 'taxes (as sales account)'],
            ['table' => 'fixed_asset_categories', 'foreignKey' => 'asset_account_id', 'label' => 'fixed asset categories (as asset account)'],
            ['table' => 'fixed_asset_categories', 'foreignKey' => 'depreciation_account_id', 'label' => 'fixed asset categories (as depreciation account)'],
            ['table' => 'fixed_asset_categories', 'foreignKey' => 'accumulated_depreciation_account_id', 'label' => 'fixed asset categories (as accumulated depreciation account)'],
            ['table' => 'fixed_asset_categories', 'foreignKey' => 'sales_account_id', 'label' => 'fixed asset categories (as sales account)'],
        ];
    }
}
