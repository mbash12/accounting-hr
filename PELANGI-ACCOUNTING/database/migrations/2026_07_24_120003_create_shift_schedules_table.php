<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_type_id')->nullable()->constrained('shift_types')->nullOnDelete();
            $table->date('date');
            $table->string('shift_code', 10);        // denormalised for fast grid rendering
            $table->boolean('is_off')->default(false);
            $table->boolean('is_holiday')->default(false);   // true if date matches a Holiday
            $table->string('holiday_name')->nullable();
            $table->boolean('is_leave')->default(false);     // future hookup with EmployeeLeaveQuota
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('created_by_user_id')->nullable();
            $table->softDeletes();

            $table->unique(['employee_id', 'date']);
            $table->index(['date', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
