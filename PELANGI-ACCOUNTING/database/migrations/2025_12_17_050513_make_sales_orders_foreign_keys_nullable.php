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
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreignId('advance_payment_id')->nullable()->change();
            $table->foreignId('other_charges_account_id')->nullable()->change();
            $table->foreignId('discount_account_id')->nullable()->change();
            $table->foreignId('updated_by_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreignId('advance_payment_id')->nullable(false)->change();
            $table->foreignId('other_charges_account_id')->nullable(false)->change();
            $table->foreignId('discount_account_id')->nullable(false)->change();
            $table->foreignId('updated_by_user_id')->nullable(false)->change();
        });
    }
};
