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
        Schema::create('cash_bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no')->index();
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('sub_module');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('from_account_id')->nullable()->comment('bank account id');
            $table->foreignId('to_account_id')->nullable()->comment('bank account id');
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');
            $table->foreignId('company_id')->nullable();
            $table->foreignId('created_by_user_id');
            $table->foreignId('posted_by_user_id')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_bank_transactions');
    }
};











