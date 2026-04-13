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
        Schema::create('fixed_asset_category_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('depreciation_method');
            $table->integer('useful_life');
            $table->boolean('is_active')->default(true);
            $table->string('asset_account_code')->nullable();
            $table->string('accumulated_depreciation_account_code')->nullable();
            $table->string('depreciation_account_code')->nullable();
            $table->string('sales_account_code')->nullable();
            $table->string('template_name');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['template_name', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_category_templates');
    }
};
