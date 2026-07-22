<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['account_templates', 'accounts'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $this->classifyPrefix($table, '1', 'current_asset', 'asset', 'operating');
            $this->classifyPrefix($table, '17', 'fixed_asset', 'fixed_asset', 'investing');
            $this->classifyPrefix($table, '171', 'fixed_asset', 'accumulated_depreciation', 'investing');
            $this->classifyPrefix($table, '18', 'other_asset', 'other_asset', 'investing');
            $this->classifyPrefix($table, '2', 'current_liability', 'liability', 'operating');
            $this->classifyPrefix($table, '23', 'long_term_liability', 'long_term_liability', 'financing');
            $this->classifyPrefix($table, '3', 'equity', 'equity', 'financing');
            $this->classifyPrefix($table, '4', 'revenue', 'revenue', 'operating');
            $this->classifyPrefix($table, '5', 'cost_of_goods_sold', 'cogs', 'operating');
            $this->classifyPrefix($table, '6', 'expense', 'expense', 'operating');
            $this->classifyPrefix($table, '63', 'expense', 'expense', 'undefined');
            $this->classifyPrefix($table, '7', 'other_income_expense', 'expense', 'undefined');
            $this->classifyPrefix($table, '71', 'other_income', 'other_revenue', 'operating');
            $this->classifyPrefix($table, '72', 'other_expense', 'other_expense', 'operating');
            $this->classifyPrefix($table, '8', 'other_income_expense', 'revenue', 'undefined');
            $this->classifyPrefix($table, '81', 'other_expense', 'other_expense', 'operating');
            $this->classifyPrefix($table, '82', 'other_income', 'other_revenue', 'operating');
            $this->classifyPrefix($table, '9', 'other_expense', 'other_expense', 'operating');

            DB::table($table)->where('code', '220200')->update(['name' => 'Hutang Lain-lain', 'description' => 'Hutang Lain-lain']);
            DB::table($table)->where('code', '220800')->update(['account_type' => 'current_liability', 'classification_type' => 'current_liability']);
            DB::table($table)->where('code', '230000')->update(['account_type' => 'long_term_liability', 'classification_type' => 'long_term_liability', 'cash_flow' => 'financing']);
        }
    }

    private function classifyPrefix(string $table, string $prefix, string $accountType, string $classification, string $cashFlow): void
    {
        DB::table($table)
            ->where('code', 'like', $prefix.'%')
            ->update([
                'account_type' => $accountType,
                'classification_type' => $classification,
                'cash_flow' => $cashFlow,
            ]);
    }

    public function down(): void
    {
        // Data repair is intentionally irreversible; restoring wrong classifications is unsafe.
    }
};
