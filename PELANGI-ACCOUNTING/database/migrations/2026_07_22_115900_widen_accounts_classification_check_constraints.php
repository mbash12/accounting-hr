<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Widen the auto-generated CHECK constraints on accounts.account_type and
     * accounts.classification_type so the application can use detailed
     * classification values (fixed_asset, cogs, other_revenue, ...) that the
     * original 5/8-value ENUMs rejected.
     *
     * This must run BEFORE 2026_07_22_120000_repair_standard_coa_classifications,
     * hence the earlier timestamp.
     */
    public function up(): void
    {
        $this->dropConstraintIfExists('accounts', 'accounts_account_type_check');
        $this->dropConstraintIfExists('accounts', 'accounts_classification_type_check');

        DB::statement(<<<'SQL'
            ALTER TABLE accounts
                ADD CONSTRAINT accounts_account_type_check
                CHECK (account_type IN (
                    'current_asset',
                    'fixed_asset',
                    'current_liability',
                    'long_term_liability',
                    'equity',
                    'revenue',
                    'expense',
                    'cost_of_goods_sold',
                    'other_asset',
                    'other_income',
                    'other_expense',
                    'other_income_expense'
                ))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE accounts
                ADD CONSTRAINT accounts_classification_type_check
                CHECK (classification_type IN (
                    'asset',
                    'liability',
                    'equity',
                    'revenue',
                    'expense',
                    'current_asset',
                    'fixed_asset',
                    'other_asset',
                    'accumulated_depreciation',
                    'current_liability',
                    'long_term_liability',
                    'cogs',
                    'cost_of_goods_sold',
                    'other_revenue',
                    'other_income',
                    'other_expense',
                    'other_income_expense'
                ))
        SQL);
    }

    /**
     * Reverting is unsafe: the repair migration stores values that fall outside
     * the original ENUMs, so restoring the narrow constraints would leave the
     * data in a violating state. The repair migration itself is intentionally
     * irreversible, so this down() is intentionally a no-op.
     */
    public function down(): void
    {
        // no-op
    }

    private function dropConstraintIfExists(string $table, string $constraint): void
    {
        $exists = DB::selectOne(
            'SELECT 1 FROM information_schema.table_constraints
             WHERE table_name = ? AND constraint_name = ?',
            [$table, $constraint]
        );

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP CONSTRAINT {$constraint}");
        }
    }
};
