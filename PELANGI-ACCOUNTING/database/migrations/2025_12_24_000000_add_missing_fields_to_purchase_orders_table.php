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
            $table->enum('order_type', ['deposit', 'standar', 'aktual'])->default('standar')->after('date');
            $table->boolean('is_closed')->default(false)->after('order_type');
            $table->date('valid_until')->nullable()->after('reference_no');
            $table->foreignId('related_order_id')->nullable()->after('job_id');
            $table->foreignId('advance_payment_id')->nullable()->after('related_order_id');
            $table->decimal('total_amount', 15, 2)->default(0)->after('total');
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->decimal('discount', 15, 2)->default(0)->after('total');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'is_closed', 
                'valid_until',
                'related_order_id',
                'advance_payment_id',
                'total_amount'
            ]);
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn([
                'discount',
                'discount_percentage',
                'tax_amount'
            ]);
        });
    }
};