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
        Schema::create('deferred_revenues', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number', 50)->unique();
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('customer_name')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->integer('total_periods');
            $table->string('recognition_method', 20)->default('straight_line');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('recognized_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->string('status', 30)->default('draft'); // draft, active, completed, cancelled
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('sales_invoice_id')->nullable();
            $table->foreignId('deferred_revenue_account_id')->nullable();
            $table->foreignId('revenue_account_id')->nullable();
            $table->foreignId('company_id');
            $table->foreignId('created_by_user_id');
            $table->foreignId('updated_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deferred_revenues');
    }
};
