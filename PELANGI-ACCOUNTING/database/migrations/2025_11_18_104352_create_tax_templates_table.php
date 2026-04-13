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
        Schema::create('tax_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->decimal('tax_percentage', 8, 2);
            $table->string('tax_type');
            $table->boolean('is_purchase_tax')->default(true);
            $table->boolean('is_sales_tax')->default(true);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('compound_tax')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('purchase_account_code')->nullable();
            $table->string('sales_account_code')->nullable();
            $table->string('template_name');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['template_name', 'code']);
            $table->index(['template_name', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_templates');
    }
};
