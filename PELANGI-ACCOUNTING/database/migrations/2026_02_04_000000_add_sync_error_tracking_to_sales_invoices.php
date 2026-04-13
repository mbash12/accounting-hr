<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->text('sync_error')->nullable()->after('sync_status');
            $table->integer('sync_retry_count')->default(0)->after('sync_error');
            $table->timestamp('last_sync_attempt_at')->nullable()->after('sync_retry_count');
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn(['sync_error', 'sync_retry_count', 'last_sync_attempt_at']);
        });
    }
};
