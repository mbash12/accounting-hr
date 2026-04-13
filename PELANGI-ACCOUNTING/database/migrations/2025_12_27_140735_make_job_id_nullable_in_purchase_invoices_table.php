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
            $table->foreignId('job_id')->nullable()->change();
        });

        // Use raw SQL for PostgreSQL foreignId modification
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN job_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable(false)->change();
        });

        // Use raw SQL for PostgreSQL foreignId modification
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN job_id SET NOT NULL');
    }
};
