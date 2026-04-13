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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50);
            $table->decimal('tax_percentage', 10, 2);
            $table->enum('tax_type', ["vat","sales_tax","service_tax","withholding_tax","excise_tax"]);
            $table->boolean('is_purchase_tax')->default(false);
            $table->boolean('is_sales_tax')->default(false);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('compound_tax')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('purchase_account_id');
            $table->foreignId('sales_account_id');
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
        Schema::dropIfExists('taxes');
    }
};
