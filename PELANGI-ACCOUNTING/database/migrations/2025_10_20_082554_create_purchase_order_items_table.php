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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->string('quantity');
            $table->string('unit_price');
            $table->string('total');
            $table->text('description');
            $table->string('received_quantity')->default('0');
            $table->foreignId('purchase_order_id');
            $table->foreignId('product_id');
            $table->foreignId('unit_id');
            $table->foreignId('tax_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
