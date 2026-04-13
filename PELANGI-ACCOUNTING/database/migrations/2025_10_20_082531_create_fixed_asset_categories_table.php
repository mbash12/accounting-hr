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
        Schema::create('fixed_asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->enum('depreciation_method', ["straight_line","declining_balance","double_declining","sum_of_years","units_of_production"]);
            $table->integer('useful_life');
            $table->boolean('is_active')->default(true);
            $table->foreignId('sales_account_id');
            $table->foreignId('asset_account_id');
            $table->foreignId('accumulated_depreciation_account_id');
            $table->foreignId('depreciation_account_id');
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
        Schema::dropIfExists('fixed_asset_categories');
    }
};
