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
        Schema::create('overtime_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('date');
            $table->decimal('hours', 4, 2);
            $table->boolean('is_holiday')->default(false);
            $table->decimal('calculated_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->text('reason')->nullable();
            $table->foreignId('approved_by_user_id')->nullable();
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
        Schema::dropIfExists('overtime_logs');
    }
};
