<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->timestamp('synced_to_inventory_at')->nullable()->after('updated_at');
            $table->string('sync_status')->nullable()->after('synced_to_inventory_at'); // pending, synced, failed
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn(['synced_to_inventory_at', 'sync_status']);
        });
    }
};
