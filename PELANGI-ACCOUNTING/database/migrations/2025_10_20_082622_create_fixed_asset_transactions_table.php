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
        Schema::create('fixed_asset_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no');
            $table->text('description');
            $table->string('journal_value');
            $table->string('asset_value');
            $table->string('difference');
            $table->enum('transaction_type', ["acquisition","depreciation","revaluation","disposal","impairment"]);
            $table->foreignId('fixed_asset_id');
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
        Schema::dropIfExists('fixed_asset_transactions');
    }
};
