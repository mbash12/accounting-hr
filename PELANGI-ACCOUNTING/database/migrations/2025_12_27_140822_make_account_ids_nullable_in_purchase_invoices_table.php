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
            $table->foreignId('other_charges_account_id')->nullable()->change();
            $table->foreignId('discount_account_id')->nullable()->change();
            $table->foreignId('advance_payment_account_id')->nullable()->change();
            $table->foreignId('updated_by_user_id')->nullable()->change();
        });

        // Use raw SQL for PostgreSQL foreignId modifications
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN other_charges_account_id DROP NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN discount_account_id DROP NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN advance_payment_account_id DROP NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN updated_by_user_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->foreignId('other_charges_account_id')->nullable(false)->change();
            $table->foreignId('discount_account_id')->nullable(false)->change();
            $table->foreignId('advance_payment_account_id')->nullable(false)->change();
            $table->foreignId('updated_by_user_id')->nullable(false)->change();
        });

        // Use raw SQL for PostgreSQL foreignId modifications
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN other_charges_account_id SET NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN discount_account_id SET NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN advance_payment_account_id SET NOT NULL');
        DB::statement('ALTER TABLE purchase_invoices ALTER COLUMN updated_by_user_id SET NOT NULL');
    }
};
