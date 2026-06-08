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
        // Drop the CHECK constraints that were created by the original enum columns.
        // PostgreSQL represents Laravel enums as VARCHAR + CHECK constraint,
        // and changing the column type via Schema::table() does not drop the constraint.
        DB::statement('ALTER TABLE receivable_payments DROP CONSTRAINT IF EXISTS receivable_payments_status_check');
        DB::statement('ALTER TABLE payable_payments DROP CONSTRAINT IF EXISTS payable_payments_status_check');

        // Change status field from enum to varchar(50) for receivable_payments
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for payable_payments
        Schema::table('payable_payments', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change status field from varchar(50) back to enum for receivable_payments
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for payable_payments
        Schema::table('payable_payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'refunded'])->nullable()->change();
        });
    }
};
