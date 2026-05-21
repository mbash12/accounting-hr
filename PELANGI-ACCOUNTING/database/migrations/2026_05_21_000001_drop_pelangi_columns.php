<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SalesOrder — drop FK first, then columns
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign(['related_order_id']);
            $table->dropColumn([
                'order_type',
                'related_order_id',
                'job_number',
                'jb_job_number',
                'client_po_number',
                'advance_payment_id',
                'job_id',
            ]);
        });

        // SalesOrderItem
        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'is_production']);
        });

        // PurchaseOrder
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'related_order_id',
                'advance_payment_id',
                'department_id',
                'sales_order_id',
                'job_id',
            ]);
        });

        // PurchaseOrderItem
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_name']);
        });

        // Product — drop FK first, then columns
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'min_order_qty']);
        });

        // Company — include_ppn does not exist in DB, only drop settings
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['settings']);
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('order_type')->default('standar');
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->foreign('related_order_id')->references('id')->on('sales_orders');
            $table->string('job_number')->nullable();
            $table->string('jb_job_number')->nullable();
            $table->string('client_po_number')->nullable();
            $table->unsignedBigInteger('advance_payment_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->string('item_name')->nullable();
            $table->boolean('is_production')->default(false);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('order_type')->default('standar');
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->unsignedBigInteger('advance_payment_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->string('item_name')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('contacts');
            $table->decimal('min_order_qty', 15, 2)->nullable();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->json('settings')->nullable();
        });
    }
};
