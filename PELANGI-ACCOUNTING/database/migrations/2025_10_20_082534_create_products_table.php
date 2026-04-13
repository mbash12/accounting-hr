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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('reorder_level', 15, 2)->default(0);
            $table->decimal('max_stock', 15, 2)->default(0);
            $table->decimal('weight', 10, 3)->default(0);
            $table->string('product_type', 50)->default('simple');
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->foreignId('unit_id');
            $table->foreignId('product_group_id');
            $table->foreignId('company_id');
            $table->foreignId('created_by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
