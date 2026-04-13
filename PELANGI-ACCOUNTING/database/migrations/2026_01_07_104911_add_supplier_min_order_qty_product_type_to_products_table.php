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
        Schema::table('products', function (Blueprint $table) {
            // Add supplier_id as foreign key to contacts table (optional)
            $table->foreignId('supplier_id')->nullable()->after('created_by_user_id')->constrained('contacts')->nullOnDelete();

            // Add minimum order quantity (optional)
            $table->decimal('min_order_qty', 15, 2)->nullable()->after('supplier_id');

            // Modify existing product_type to be more specific for goods/services
            $table->string('product_type', 50)->default('good')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'min_order_qty']);
            $table->string('product_type', 50)->default('simple')->change();
        });
    }
};
