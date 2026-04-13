<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE cash_receipts ALTER COLUMN reference_no DROP NOT NULL');
        DB::statement('ALTER TABLE cash_disbursements ALTER COLUMN reference_no DROP NOT NULL');
        DB::statement('ALTER TABLE cash_transfers ALTER COLUMN reference_no DROP NOT NULL');
        DB::statement('ALTER TABLE cash_bank_transactions ALTER COLUMN reference_no DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE cash_receipts ALTER COLUMN reference_no SET NOT NULL');
        DB::statement('ALTER TABLE cash_disbursements ALTER COLUMN reference_no SET NOT NULL');
        DB::statement('ALTER TABLE cash_transfers ALTER COLUMN reference_no SET NOT NULL');
        DB::statement('ALTER TABLE cash_bank_transactions ALTER COLUMN reference_no SET NOT NULL');
    }
};
