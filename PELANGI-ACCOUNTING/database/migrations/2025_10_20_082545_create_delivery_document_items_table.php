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
        Schema::create('delivery_document_items', function (Blueprint $table) {
            $table->id();
            $table->string('total_quantity');
            $table->text('description');
            $table->json('delivery_allocation');
            $table->foreignId('delivery_document_id');
            $table->foreignId('product_id');
            $table->foreignId('unit_id');
            $table->foreignId('warehouse_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_document_items');
    }
};
