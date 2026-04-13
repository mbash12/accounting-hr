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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50);
            $table->string('location', 200)->nullable();
            $table->date('acquisition_date');
            $table->text('description')->nullable();
            $table->decimal('acquisition_value', 15, 2);
            $table->decimal('monthly_depreciation', 15, 2)->default(0);
            $table->enum('depreciation_method', ["straight_line","declining_balance","double_declining","sum_of_years","units_of_production"])->default('straight_line');
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->integer('useful_life');
            $table->decimal('book_value', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id');
            $table->foreignId('department_id');
            $table->foreignId('transaction_in_id');
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
        Schema::dropIfExists('fixed_assets');
    }
};
