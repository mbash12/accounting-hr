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
        Schema::create('payable_payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->string('reference_no');
            $table->text('description');
            $table->string('total_payment');
            $table->enum('payment_method', ["cash","bank_transfer","check","credit_card","debit_card","online_payment","other"]);
            $table->enum('status', ["pending","completed","failed","cancelled","refunded"]);
            $table->foreignId('supplier_id');
            $table->foreignId('bank_account_id');
            $table->foreignId('job_id');
            $table->foreignId('company_id');
            $table->foreignId('created_by_user_id');
            $table->foreignId('updated_by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payable_payments');
    }
};
