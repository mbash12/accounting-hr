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
        Schema::table('delivery_document_items', function (Blueprint $table) {
            $table->foreignId('sales_order_item_id')->nullable()->after('delivery_document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_document_items', function (Blueprint $table) {
            $table->dropColumn('sales_order_item_id');
        });
    }
};
