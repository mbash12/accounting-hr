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
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->date('statement_date');
            $table->string('statement_balance');
            $table->string('book_balance');
            $table->date('reconciliation_date');
            $table->enum('status', ["pending","in_progress","completed","failed"]);
            $table->timestamp('reconciled_at')->nullable();
            $table->string('difference')->default('0');
            $table->foreignId('bank_account_id');
            $table->foreignId('reconciled_by_user_id');
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
        Schema::dropIfExists('bank_reconciliations');
    }
};
