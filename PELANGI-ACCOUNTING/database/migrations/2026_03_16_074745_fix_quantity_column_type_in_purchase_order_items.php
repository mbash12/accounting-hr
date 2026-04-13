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
        // Use raw SQL for PostgreSQL with USING clause
        DB::statement('
            ALTER TABLE purchase_order_items
            ALTER COLUMN quantity
            TYPE DECIMAL(15,2)
            USING quantity::DECIMAL(15,2)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to varchar
        DB::statement('
            ALTER TABLE purchase_order_items
            ALTER COLUMN quantity
            TYPE VARCHAR
            USING quantity::VARCHAR
        ');
    }
};
