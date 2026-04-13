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
        Schema::create('overpayment_receipts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no');
            $table->text('description');
            $table->string('amount');
            $table->enum('status', ["pending","processed","failed","cancelled"]);
            $table->foreignId('supplier_id');
            $table->foreignId('payable_payment_id');
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
        Schema::dropIfExists('overpayment_receipts');
    }
};
