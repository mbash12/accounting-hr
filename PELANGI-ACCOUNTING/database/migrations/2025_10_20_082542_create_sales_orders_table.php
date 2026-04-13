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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50);
            $table->enum('order_type', ["standard","cash","credit","consignment","service"])->default('standard');
            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ["draft","confirmed","partially_delivered","completed","cancelled"])->default('draft');
            $table->foreignId('job_id');
            $table->foreignId('customer_id');
            $table->foreignId('advance_payment_id');
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
        Schema::dropIfExists('sales_orders');
    }
};
