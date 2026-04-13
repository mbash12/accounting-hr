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
        DB::statement('ALTER TABLE cash_receipts ALTER COLUMN recipient_id DROP NOT NULL');
        DB::statement('ALTER TABLE cash_disbursements ALTER COLUMN recipient_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE cash_receipts ALTER COLUMN recipient_id SET NOT NULL');
        DB::statement('ALTER TABLE cash_disbursements ALTER COLUMN recipient_id SET NOT NULL');
    }
};

