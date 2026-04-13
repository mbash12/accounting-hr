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
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->change();
        });

        // Use raw SQL for PostgreSQL enum modification
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN status DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('reference_no')->nullable(false)->change();
        });

        // Use raw SQL for PostgreSQL enum modification
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN status SET NOT NULL');
    }
};
