<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('status');
        });

        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->foreignId('goods_receipt_item_id')->nullable()->after('purchase_return_id');
        });

        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->json('return_meta')->nullable()->after('is_locked');
        });

        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->decimal('returned_quantity', 15, 2)->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->dropColumn('returned_quantity');
        });

        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropColumn('return_meta');
        });

        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->dropColumn('goods_receipt_item_id');
        });

        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropColumn('is_locked');
        });
    }
};
