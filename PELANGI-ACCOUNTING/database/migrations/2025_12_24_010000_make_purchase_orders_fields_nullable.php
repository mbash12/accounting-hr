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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->foreignId('job_id')->nullable()->change();
            $table->foreignId('department_id')->nullable()->change();
            $table->foreignId('related_order_id')->nullable()->change();
            $table->foreignId('advance_payment_id')->nullable()->change();
            $table->foreignId('other_charges_account_id')->nullable()->change();
            $table->foreignId('discount_account_id')->nullable()->change();
            $table->foreignId('updated_by_user_id')->nullable()->change();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('unit_id')->nullable()->change();
            $table->foreignId('tax_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('reference_no')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->foreignId('job_id')->nullable(false)->change();
            $table->foreignId('department_id')->nullable(false)->change();
            $table->foreignId('related_order_id')->nullable(false)->change();
            $table->foreignId('advance_payment_id')->nullable(false)->change();
            $table->foreignId('other_charges_account_id')->nullable(false)->change();
            $table->foreignId('discount_account_id')->nullable(false)->change();
            $table->foreignId('updated_by_user_id')->nullable(false)->change();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreignId('unit_id')->nullable(false)->change();
            $table->foreignId('tax_id')->nullable(false)->change();
        });
    }
};