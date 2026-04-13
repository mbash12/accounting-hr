<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing null values to 0
        DB::statement("UPDATE sales_orders SET
            discount = COALESCE(discount, 0),
            tax_amount = COALESCE(tax_amount, 0),
            subtotal = COALESCE(subtotal, 0),
            total_amount = COALESCE(total_amount, 0),
            other_charges = COALESCE(other_charges, 0)
        WHERE
            discount IS NULL
            OR tax_amount IS NULL
            OR subtotal IS NULL
            OR total_amount IS NULL
            OR other_charges IS NULL");

        // Ensure the columns have proper defaults in PostgreSQL
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN discount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN tax_amount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN subtotal SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN total_amount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN other_charges SET DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset defaults (though they should remain 0 as per original migration)
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN discount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN tax_amount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN subtotal SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN total_amount SET DEFAULT 0");
        DB::statement("ALTER TABLE sales_orders ALTER COLUMN other_charges SET DEFAULT 0");
    }
};
