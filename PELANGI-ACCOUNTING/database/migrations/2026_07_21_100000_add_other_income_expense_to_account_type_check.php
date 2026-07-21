<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_account_type_check');

        DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_account_type_check CHECK (account_type IN ('current_asset', 'fixed_asset', 'other_asset', 'current_liability', 'long_term_liability', 'equity', 'revenue', 'expense', 'cost_of_goods_sold', 'other_income', 'other_expense', 'other_income_expense'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_account_type_check');

        DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_account_type_check CHECK (account_type IN ('current_asset', 'fixed_asset', 'other_asset', 'current_liability', 'long_term_liability', 'equity', 'revenue', 'expense', 'cost_of_goods_sold', 'other_income', 'other_expense'))");
    }
};
