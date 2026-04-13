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
        DB::statement('ALTER TABLE purchase_invoice_items ALTER COLUMN quantity TYPE DECIMAL(15,2) USING quantity::decimal');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE purchase_invoice_items ALTER COLUMN quantity TYPE VARCHAR(255)');
    }
};
