<?php

namespace App\Services;

use App\Models\Account;
use App\Models\PayrollPeriod;
use App\Models\Payslip;
use App\Models\SalaryComponent;
use App\Services\DataCleanup\DataCleanupRegistry;
use App\Services\DataCleanup\DatasetDefinition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class DataCleanupService
{
    public const DATASET_CHART_OF_ACCOUNTS = 'chart_of_accounts';
    public const DATASET_ACCOUNT_MAPPINGS = 'account_mappings';
    public const DATASET_OPENING_BALANCES = 'opening_balances';
    public const DATASET_TAXES = 'taxes';
    public const DATASET_FIXED_ASSET_CATEGORIES = 'fixed_asset_categories';

    public const MODE_CASCADE = 'cascade';
    public const MODE_NULLIFY = 'nullify';

    public function __construct(
        private readonly DataCleanupRegistry $registry = new DataCleanupRegistry,
    ) {}

    /**
     * @return array<string, array{label: string, group: string, description: string, danger: bool}>
     */
    public function datasets(): array
    {
        $out = [];
        foreach ($this->registry->all() as $key => $def) {
            $out[$key] = [
                'label' => __($def->label),
                'group' => $def->group,
                'description' => __($def->description),
                'danger' => $def->danger,
            ];
        }

        return $out;
    }

    /**
     * @return array<string, int>
     */
    public function counts(int $companyId): array
    {
        $counts = [];
        foreach ($this->registry->all() as $key => $def) {
            $counts[$key] = $this->countDataset($def, $companyId);
        }

        return $counts;
    }

    /**
     * @param  list<string>  $keys
     * @return array{ok: bool, rows: list<array{key: string, label: string, count: int, related: list<array{label: string, count: int, action: string}>}>, errors: list<string>}
     */
    public function preview(array $keys, int $companyId, string $mode): array
    {
        $mode = $this->assertMode($mode);
        $defs = $this->resolveDefinitions($keys);
        $errors = [];
        $rows = [];

        if ($mode === self::MODE_NULLIFY) {
            foreach ($defs as $def) {
                foreach ($def->nullifyBlockers as $blocker) {
                    if (!$this->tableHasColumn($blocker['table'], $blocker['column'])) {
                        continue;
                    }

                    $count = $this->countBlockerRows($def, $companyId, $blocker);
                    if ($count > 0) {
                        $errors[] = __('Cannot nullify :dataset while :label still reference it (:count). Clear those records first or use cascade delete.', [
                            'dataset' => $def->label,
                            'label' => $blocker['label'],
                            'count' => $count,
                        ]);
                    }
                }
            }
        }

        foreach ($defs as $def) {
            $related = [];

            foreach ($def->children as $child) {
                if (!$this->tableHasColumn($child['table'], $child['fk'])) {
                    continue;
                }
                $related[] = [
                    'label' => $child['table'],
                    'count' => $this->countChildRows($def, $companyId, $child),
                    'action' => 'delete',
                ];
            }

            if ($mode === self::MODE_CASCADE) {
                foreach ($def->cascadeRelated as $relatedKey) {
                    $relatedDef = $this->registry->get($relatedKey);
                    if (!$relatedDef) {
                        continue;
                    }
                    $related[] = [
                        'label' => $relatedDef->label,
                        'count' => $this->countDataset($relatedDef, $companyId),
                        'action' => 'cascade_delete',
                    ];
                }
            }

            if ($mode === self::MODE_NULLIFY) {
                foreach ($def->nullify as $rule) {
                    if (!$this->tableHasColumn($rule['table'], $rule['column'])) {
                        continue;
                    }
                    $related[] = [
                        'label' => $rule['table'] . '.' . $rule['column'],
                        'count' => $this->countNullifyTargets($def, $companyId, $rule),
                        'action' => 'nullify',
                    ];
                }
            }

            $rows[] = [
                'key' => $def->key,
                'label' => $def->label,
                'count' => $this->countDataset($def, $companyId),
                'related' => $related,
            ];
        }

        return [
            'ok' => $errors === [],
            'rows' => $rows,
            'errors' => $errors,
        ];
    }

    /**
     * @param  list<string>|string  $keys
     * @return array{deleted: array<string, int>, nullified: array<string, int>}
     */
    public function clear(array|string $keys, int $companyId, string $mode = self::MODE_CASCADE): array
    {
        if (is_string($keys)) {
            $keys = [$keys];
        }

        $mode = $this->assertMode($mode);
        $defs = $this->resolveDefinitions($keys);

        $preview = $this->preview($keys, $companyId, $mode);
        if (!$preview['ok']) {
            throw new RuntimeException(implode(' ', $preview['errors']));
        }

        usort($defs, fn (DatasetDefinition $a, DatasetDefinition $b) => $a->order <=> $b->order);

        return DB::transaction(function () use ($defs, $companyId, $mode) {
            $deleted = [];
            $nullified = [];
            $clearedKeys = [];

            foreach ($defs as $def) {
                if (isset($clearedKeys[$def->key])) {
                    continue;
                }

                $result = $this->clearDefinition($def, $companyId, $mode, $clearedKeys);
                foreach ($result['deleted'] as $k => $v) {
                    $deleted[$k] = ($deleted[$k] ?? 0) + $v;
                }
                foreach ($result['nullified'] as $k => $v) {
                    $nullified[$k] = ($nullified[$k] ?? 0) + $v;
                }
            }

            return [
                'deleted' => $deleted,
                'nullified' => $nullified,
            ];
        });
    }

    /**
     * @param  array<string, true>  $clearedKeys
     * @return array{deleted: array<string, int>, nullified: array<string, int>}
     */
    private function clearDefinition(DatasetDefinition $def, int $companyId, string $mode, array &$clearedKeys): array
    {
        $deleted = [];
        $nullified = [];

        if ($def->handler === 'chart_of_accounts') {
            return $this->clearChartOfAccounts($companyId, $mode, $clearedKeys);
        }

        if ($def->handler === 'payroll_periods') {
            $deleted[$def->key] = $this->clearPayrollPeriods($companyId);
            $clearedKeys[$def->key] = true;

            return compact('deleted', 'nullified');
        }

        if ($mode === self::MODE_CASCADE) {
            foreach ($def->cascadeRelated as $relatedKey) {
                if (isset($clearedKeys[$relatedKey])) {
                    continue;
                }
                $relatedDef = $this->registry->get($relatedKey);
                if (!$relatedDef) {
                    continue;
                }
                $nested = $this->clearDefinition($relatedDef, $companyId, self::MODE_CASCADE, $clearedKeys);
                foreach ($nested['deleted'] as $k => $v) {
                    $deleted[$k] = ($deleted[$k] ?? 0) + $v;
                }
            }
        }

        if ($mode === self::MODE_NULLIFY) {
            foreach ($def->nullify as $rule) {
                $count = $this->applyNullify($def, $companyId, $rule);
                if ($count > 0) {
                    $nullified[$rule['table'] . '.' . $rule['column']] = $count;
                }
            }
        }

        $ids = $this->parentIds($def, $companyId);
        foreach ($def->children as $child) {
            $this->deleteChildRows($child, $ids);
        }

        $deleted[$def->key] = $this->forceDeleteByCompany($def->model, $companyId);
        $clearedKeys[$def->key] = true;

        return compact('deleted', 'nullified');
    }

    /**
     * @param  array<string, true>  $clearedKeys
     * @return array{deleted: array<string, int>, nullified: array<string, int>}
     */
    private function clearChartOfAccounts(int $companyId, string $mode, array &$clearedKeys): array
    {
        $def = $this->registry->get(self::DATASET_CHART_OF_ACCOUNTS);
        $accountIds = Account::withTrashed()
            ->where('company_id', $companyId)
            ->pluck('id');

        $deleted = [
            self::DATASET_CHART_OF_ACCOUNTS => 0,
        ];
        $nullified = [];

        if ($accountIds->isEmpty()) {
            $clearedKeys[self::DATASET_CHART_OF_ACCOUNTS] = true;

            return compact('deleted', 'nullified');
        }

        if ($mode === self::MODE_CASCADE) {
            foreach ($def->cascadeRelated as $relatedKey) {
                if (isset($clearedKeys[$relatedKey])) {
                    continue;
                }
                $relatedDef = $this->registry->get($relatedKey);
                if (!$relatedDef) {
                    continue;
                }
                $nested = $this->clearDefinition($relatedDef, $companyId, self::MODE_CASCADE, $clearedKeys);
                foreach ($nested['deleted'] as $k => $v) {
                    $deleted[$k] = ($deleted[$k] ?? 0) + $v;
                }
            }
        }

        // Always nullify optional refs so FK constraints do not block account delete.
        foreach ($def->nullify as $rule) {
            $count = $this->applyNullify($def, $companyId, $rule);
            if ($count > 0) {
                $nullified[$rule['table'] . '.' . $rule['column']] = $count;
            }
        }

        if ($mode === self::MODE_CASCADE || $mode === self::MODE_NULLIFY) {
            // Journal usage still blocks COA clear in both modes.
            $this->assertNoJournalUsage($accountIds->all());
        }

        $deleted[self::DATASET_CHART_OF_ACCOUNTS] = Account::withoutEvents(function () use ($companyId) {
            Account::withTrashed()
                ->where('company_id', $companyId)
                ->update([
                    'parent_id' => null,
                    'classification_id' => null,
                ]);

            return Account::withTrashed()
                ->where('company_id', $companyId)
                ->forceDelete();
        });

        $clearedKeys[self::DATASET_CHART_OF_ACCOUNTS] = true;

        return compact('deleted', 'nullified');
    }

    private function clearPayrollPeriods(int $companyId): int
    {
        $periodIds = PayrollPeriod::query()
            ->when(
                $this->modelUsesSoftDeletes(PayrollPeriod::class),
                fn ($q) => $q->withTrashed()
            )
            ->where('company_id', $companyId)
            ->pluck('id');

        if ($periodIds->isEmpty() && Schema::hasTable('payslips')) {
            // Still clear orphan payslips for company.
        }

        if (Schema::hasTable('payslip_items') && Schema::hasTable('payslips')) {
            $payslipIds = DB::table('payslips')
                ->where('company_id', $companyId)
                ->pluck('id');

            if ($payslipIds->isNotEmpty()) {
                DB::table('payslip_items')->whereIn('payslip_id', $payslipIds)->delete();
            }
        }

        if (Schema::hasTable('payslips')) {
            if (in_array(SoftDeletes::class, class_uses_recursive(Payslip::class), true)) {
                Payslip::withTrashed()->where('company_id', $companyId)->forceDelete();
            } else {
                Payslip::query()->where('company_id', $companyId)->delete();
            }
        }

        return $this->forceDeleteByCompany(PayrollPeriod::class, $companyId);
    }

    /**
     * @param  list<int>  $accountIds
     */
    private function assertNoJournalUsage(array $accountIds): void
    {
        if ($accountIds === []) {
            return;
        }

        if (Schema::hasTable('journal_entry_items')
            && DB::table('journal_entry_items')->whereIn('account_id', $accountIds)->exists()) {
            throw new RuntimeException(
                __('Cannot clear Chart of Accounts while journal entry items still reference these accounts. Clear journals first or include Journal Entries in the selection.')
            );
        }

        if (Schema::hasTable('account_journal_entry')
            && DB::table('account_journal_entry')->whereIn('account_id', $accountIds)->exists()) {
            throw new RuntimeException(
                __('Cannot clear Chart of Accounts while journal entries still reference these accounts. Clear journals first or include Journal Entries in the selection.')
            );
        }
    }

    /**
     * @param  list<string>  $keys
     * @return list<DatasetDefinition>
     */
    private function resolveDefinitions(array $keys): array
    {
        if ($keys === []) {
            throw new RuntimeException(__('No datasets selected.'));
        }

        $defs = [];
        foreach ($keys as $key) {
            $def = $this->registry->get($key);
            if (!$def) {
                throw new RuntimeException(__('Unknown dataset: :dataset', ['dataset' => $key]));
            }
            $defs[] = $def;
        }

        return $defs;
    }

    private function assertMode(string $mode): string
    {
        if (!in_array($mode, [self::MODE_CASCADE, self::MODE_NULLIFY], true)) {
            throw new RuntimeException(__('Unknown side-effect mode: :mode', ['mode' => $mode]));
        }

        return $mode;
    }

    private function countDataset(DatasetDefinition $def, int $companyId): int
    {
        if ($def->handler === 'payroll_periods') {
            return $this->forceCountByCompany(PayrollPeriod::class, $companyId)
                + $this->forceCountByCompany(Payslip::class, $companyId);
        }

        if (!$def->model) {
            return 0;
        }

        return $this->forceCountByCompany($def->model, $companyId);
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    private function forceCountByCompany(string $modelClass, int $companyId): int
    {
        if (!class_exists($modelClass)) {
            return 0;
        }

        $query = $modelClass::query();
        if ($this->modelUsesSoftDeletes($modelClass)) {
            $query->withTrashed();
        }

        if (!Schema::hasColumn((new $modelClass)->getTable(), 'company_id')) {
            return 0;
        }

        return $query->where('company_id', $companyId)->count();
    }

    /**
     * @param  array{table: string, fk: string}  $child
     */
    private function countChildRows(DatasetDefinition $def, int $companyId, array $child): int
    {
        $ids = $this->parentIds($def, $companyId);
        if ($ids === []) {
            return 0;
        }

        return DB::table($child['table'])->whereIn($child['fk'], $ids)->count();
    }

    /**
     * @param  array{table: string, column: string, label: string}  $blocker
     */
    private function countBlockerRows(DatasetDefinition $def, int $companyId, array $blocker): int
    {
        $ids = $this->parentIds($def, $companyId);
        if ($ids === []) {
            return 0;
        }

        return DB::table($blocker['table'])->whereIn($blocker['column'], $ids)->count();
    }

    /**
     * @param  array{table: string, column: string, company_scoped?: bool}  $rule
     */
    private function countNullifyTargets(DatasetDefinition $def, int $companyId, array $rule): int
    {
        $ids = $this->parentIds($def, $companyId);
        if ($ids === []) {
            return 0;
        }

        $query = DB::table($rule['table'])->whereIn($rule['column'], $ids);
        if (!empty($rule['company_scoped']) && Schema::hasColumn($rule['table'], 'company_id')) {
            $query->where('company_id', $companyId);
        }

        return $query->count();
    }

    /**
     * @param  array{table: string, column: string, company_scoped?: bool}  $rule
     */
    private function applyNullify(DatasetDefinition $def, int $companyId, array $rule): int
    {
        if (!$this->tableHasColumn($rule['table'], $rule['column'])) {
            return 0;
        }

        $ids = $this->parentIds($def, $companyId);
        if ($ids === []) {
            return 0;
        }

        // Special-case salary components via model for observers consistency.
        if ($rule['table'] === 'salary_components' && $rule['column'] === 'account_id') {
            return SalaryComponent::query()
                ->where('company_id', $companyId)
                ->whereIn('account_id', $ids)
                ->update(['account_id' => null]);
        }

        $query = DB::table($rule['table'])->whereIn($rule['column'], $ids);
        if (!empty($rule['company_scoped']) && Schema::hasColumn($rule['table'], 'company_id')) {
            $query->where('company_id', $companyId);
        }

        return $query->update([$rule['column'] => null]);
    }

    /**
     * @return list<int|string>
     */
    private function parentIds(DatasetDefinition $def, int $companyId): array
    {
        if (!$def->model || !class_exists($def->model)) {
            return [];
        }

        $query = $def->model::query();
        if ($this->modelUsesSoftDeletes($def->model)) {
            $query->withTrashed();
        }

        return $query->where('company_id', $companyId)->pluck('id')->all();
    }

    /**
     * @param  array{table: string, fk: string}  $child
     * @param  list<int|string>  $parentIds
     */
    private function deleteChildRows(array $child, array $parentIds): void
    {
        if ($parentIds === [] || !$this->tableHasColumn($child['table'], $child['fk'])) {
            return;
        }

        DB::table($child['table'])->whereIn($child['fk'], $parentIds)->delete();
    }

    /**
     * @param  class-string<Model>|null  $modelClass
     */
    private function forceDeleteByCompany(?string $modelClass, int $companyId): int
    {
        if (!$modelClass || !class_exists($modelClass)) {
            return 0;
        }

        if (!Schema::hasColumn((new $modelClass)->getTable(), 'company_id')) {
            return 0;
        }

        return $modelClass::withoutEvents(function () use ($modelClass, $companyId) {
            $query = $modelClass::query();
            if ($this->modelUsesSoftDeletes($modelClass)) {
                $query->withTrashed();

                return $query->where('company_id', $companyId)->forceDelete();
            }

            return $query->where('company_id', $companyId)->delete();
        });
    }

    /**
     * @param  class-string  $modelClass
     */
    private function modelUsesSoftDeletes(string $modelClass): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($modelClass), true);
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        return Schema::hasTable($table) && Schema::hasColumn($table, $column);
    }
}
