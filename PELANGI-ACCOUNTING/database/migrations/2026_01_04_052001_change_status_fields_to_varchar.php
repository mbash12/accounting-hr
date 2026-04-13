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
        // Change status field from enum to varchar(50) for sales orders
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for purchase orders
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for goods receipts
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for sales invoices
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for purchase invoices
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for sales returns
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Change status field from enum to varchar(50) for purchase returns
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change status field from varchar(50) back to enum for sales orders
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->enum('status', ["draft","confirmed","partially_delivered","completed","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for purchase orders
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->enum('status', ["draft","sent","confirmed","partially_received","completed","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for goods receipts
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->enum('status', ["pending","received","inspected","approved","rejected","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for sales invoices
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->enum('status', ["draft","sent","overdue","paid","partially_paid","written_off","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for purchase invoices
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->enum('status', ["draft","received","approved","paid","partially_paid","disputed","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for sales returns
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->enum('status', ["requested","approved","received","processed","rejected","cancelled"])->nullable()->change();
        });

        // Change status field from varchar(50) back to enum for purchase returns
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->enum('status', ["requested","approved","shipped","received","processed","rejected","cancelled"])->nullable()->change();
        });
    }
};
