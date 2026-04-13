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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50);
            $table->date('date');
            $table->date('due_date');
            $table->boolean('is_paid')->default(false);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            $table->boolean('is_advance_payment_invoice')->default(false);
            $table->boolean('is_settlement_invoice')->default(false);
            $table->enum('status', ["draft","sent","overdue","paid","partially_paid","written_off","cancelled"])->default('draft');
            $table->foreignId('customer_id');
            $table->foreignId('sales_order_id');
            $table->foreignId('job_id');
            $table->foreignId('other_charges_account_id');
            $table->foreignId('discount_account_id');
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
        Schema::dropIfExists('sales_invoices');
    }
};
