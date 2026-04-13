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
        Schema::create('payable_payment_items', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('amount');
            $table->string('paid_amount');
            $table->string('discount_amount')->default('0');
            $table->string('write_off_amount')->default('0');
            $table->string('set_payment');
            $table->foreignId('payable_payment_id');
            $table->foreignId('purchase_invoice_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payable_payment_items');
    }
};
