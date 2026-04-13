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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('name', 200);
            $table->enum('type', ['allowance', 'deduction']);
            $table->boolean('is_fixed')->default(false);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_bpjs_base')->default(true);
            $table->foreignId('account_id')->nullable()->constrained('accounts');
            $table->boolean('is_active')->default(true);
            $table->foreignId('company_id')->nullable();
            $table->foreignId('created_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};
