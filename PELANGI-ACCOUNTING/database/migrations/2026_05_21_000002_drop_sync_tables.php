<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('invoice_sync_jobs');

        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'synced_to_inventory_at',
                'sync_status',
                'sync_error',
                'sync_retry_count',
                'last_sync_attempt_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->timestamp('synced_to_inventory_at')->nullable();
            $table->string('sync_status')->nullable()->default('pending');
            $table->text('sync_error')->nullable();
            $table->integer('sync_retry_count')->default(0);
            $table->timestamp('last_sync_attempt_at')->nullable();
        });
    }
};
