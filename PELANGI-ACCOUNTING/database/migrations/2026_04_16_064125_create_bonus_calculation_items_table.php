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
        Schema::create('bonus_calculation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_calculation_id')->constrained('bonus_calculations')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees');
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
        Schema::dropIfExists('bonus_calculation_items');
    }
};
