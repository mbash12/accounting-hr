<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->foreignId('goods_receipt_item_id')->nullable()->after('purchase_order_item_id')->constrained('goods_receipt_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->dropForeign(['goods_receipt_item_id']);
            $table->dropColumn('goods_receipt_item_id');
        });
    }
};
