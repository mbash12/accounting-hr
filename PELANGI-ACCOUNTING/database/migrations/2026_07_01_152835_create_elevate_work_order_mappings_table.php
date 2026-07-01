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
        Schema::create('elevate_work_order_mappings', function (Blueprint $table) {
            $table->id();

            $table->string('work_order_id')->unique();
            $table->string('work_order_number')->nullable()->comment('Human-readable WO number, used as reference_no');

            $table->foreignId('company_id')->constrained('companies')->comment('Target company');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->comment('Resolved customer contact');
            $table->foreignId('sales_invoice_id')->nullable()->constrained('sales_invoices')->comment('Generated sales invoice');
            $table->foreignId('receivable_payment_id')->nullable()->constrained('receivable_payments')->comment('Generated receivable payment');

            $table->string('status')->default('pending')
                ->comment('pending | contact_resolved | invoice_created | payment_created | completed | failed');
            $table->text('error_message')->nullable()->comment('Last error if status = failed');

            $table->json('payload')->nullable()->comment('Original Elevate webhook payload');

            $table->timestamps();

            $table->index('work_order_id');
            $table->index('status');
            $table->index(['company_id', 'work_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elevate_work_order_mappings');
    }
};
