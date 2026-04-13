<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to use raw SQL to modify enum columns
        \DB::statement("ALTER TABLE sales_orders DROP CONSTRAINT IF EXISTS sales_orders_order_type_check");
        \DB::statement("ALTER TABLE sales_orders ADD CONSTRAINT sales_orders_order_type_check CHECK (order_type IN ('deposit', 'standar', 'aktual'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE sales_orders DROP CONSTRAINT IF EXISTS sales_orders_order_type_check");
        \DB::statement("ALTER TABLE sales_orders ADD CONSTRAINT sales_orders_order_type_check CHECK (order_type IN ('standard', 'cash', 'credit', 'consignment', 'service'))");
    }
};
