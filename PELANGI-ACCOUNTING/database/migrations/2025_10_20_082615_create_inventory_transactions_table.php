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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ["purchase","sale","return_in","return_out","adjustment","transfer_in","transfer_out","opname"]);
            $table->string('quantity');
            $table->string('unit_cost')->default('0');
            $table->string('total_cost')->default('0');
            $table->string('reference_no');
            $table->date('date');
            $table->text('description');
            $table->string('batch_number', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('product_id');
            $table->foreignId('warehouse_id');
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
        Schema::dropIfExists('inventory_transactions');
    }
};
