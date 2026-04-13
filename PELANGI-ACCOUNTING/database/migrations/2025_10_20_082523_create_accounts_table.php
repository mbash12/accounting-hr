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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('name', 200);
            $table->enum('account_type', ["current_asset","fixed_asset","current_liability","long_term_liability","equity","revenue","expense","cost_of_goods_sold"]);
            $table->boolean('is_cash_bank')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('level')->default(1);
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->foreignId('parent_id');
            $table->foreignId('account_classification_id');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
