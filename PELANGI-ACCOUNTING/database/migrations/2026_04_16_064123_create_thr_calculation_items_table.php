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
        Schema::create('thr_calculation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thr_calculation_id')->constrained('thr_calculations')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('basic_salary', 15, 2);
            $table->integer('months_service');
            $table->decimal('amount', 15, 2);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->foreignId('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thr_calculation_items');
    }
};
