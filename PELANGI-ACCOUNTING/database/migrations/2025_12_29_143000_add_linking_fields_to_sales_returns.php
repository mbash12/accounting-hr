<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('status');
        });

        Schema::table('sales_return_items', function (Blueprint $table) {
            $table->foreignId('delivery_document_item_id')->nullable()->after('sales_return_id');
            $table->decimal('returned_quantity', 15, 2)->default(0)->after('quantity');
        });

        Schema::table('delivery_documents', function (Blueprint $table) {
            $table->json('return_meta')->nullable()->after('delivery_meta');
        });

        Schema::table('delivery_document_items', function (Blueprint $table) {
            $table->decimal('returned_quantity', 15, 2)->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_document_items', function (Blueprint $table) {
            $table->dropColumn('returned_quantity');
        });

        Schema::table('delivery_documents', function (Blueprint $table) {
            $table->dropColumn('return_meta');
        });

        Schema::table('sales_return_items', function (Blueprint $table) {
            $table->dropColumn(['delivery_document_item_id', 'returned_quantity']);
        });

        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropColumn('is_locked');
        });
    }
};
