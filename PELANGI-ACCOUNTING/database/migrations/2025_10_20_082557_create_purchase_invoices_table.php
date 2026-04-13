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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date');
            $table->boolean('is_paid')->default(false);
            $table->string('reference_no');
            $table->text('description');
            $table->string('other_charges')->default('0');
            $table->string('discount')->default('0');
            $table->string('advance_payment')->default('0');
            $table->string('subtotal')->default('0');
            $table->string('tax_amount')->default('0');
            $table->string('total')->default('0');
            $table->string('paid_amount')->default('0');
            $table->string('outstanding_amount')->default('0');
            $table->enum('status', ["draft","received","approved","paid","partially_paid","disputed","cancelled"]);
            $table->foreignId('supplier_id');
            $table->foreignId('purchase_order_id');
            $table->foreignId('job_id');
            $table->foreignId('other_charges_account_id');
            $table->foreignId('discount_account_id');
            $table->foreignId('advance_payment_account_id');
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
        Schema::dropIfExists('purchase_invoices');
    }
};
