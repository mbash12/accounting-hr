<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing check constraint
        DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_account_type_check");
        
        // Add the new check constraint with expanded values
        DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_account_type_check CHECK (account_type IN ('current_asset', 'fixed_asset', 'other_asset', 'current_liability', 'long_term_liability', 'equity', 'revenue', 'expense', 'cost_of_goods_sold', 'other_income', 'other_expense'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new constraint
        DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_account_type_check");
        
        // Restore the original constraint
        DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_account_type_check CHECK (account_type IN ('current_asset', 'fixed_asset', 'current_liability', 'long_term_liability', 'equity', 'revenue', 'expense', 'cost_of_goods_sold'))");
    }
};
