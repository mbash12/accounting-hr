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
        Schema::create('account_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('document_type', 50); // sales_order, sales_delivery, sales_invoice, sales_return, purchase_order, goods_receipt, purchase_invoice, purchase_return, cash_receipt, cash_disbursement, advance_receipt, advance_disbursement
            $table->string('mapping_type', 50); // revenue, discount, tax, other_charges, cogs, inventory, accounts_receivable, accounts_payable, advance_receivable, advance_payable, gain, loss, sales, purchases
            $table->foreignId('account_id')->constrained()->onDelete('restrict');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Ensure unique mapping per company, document type, and mapping type
            $table->unique(['company_id', 'document_type', 'mapping_type'], 'unique_account_mapping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_mappings');
    }
};
