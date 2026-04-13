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
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->foreignId('sales_invoice_id')->nullable()->change();
            $table->foreignId('job_id')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->foreignId('sales_invoice_id')->nullable(false)->change();
            $table->foreignId('job_id')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
