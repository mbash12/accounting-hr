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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_no');
            $table->date('date');
            $table->string('reference_no');
            $table->text('description');
            $table->string('other_charges')->default('0');
            $table->string('discount')->default('0');
            $table->string('subtotal')->default('0');
            $table->string('tax_amount')->default('0');
            $table->string('total')->default('0');
            $table->enum('status', ["draft","sent","confirmed","partially_received","completed","cancelled"]);
            $table->foreignId('supplier_id');
            $table->foreignId('job_id');
            $table->foreignId('department_id');
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
        Schema::dropIfExists('purchase_orders');
    }
};
