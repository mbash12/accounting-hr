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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->string('number', 50)->unique();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('payroll_period_id')->constrained('payroll_periods');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('total_allowance', 15, 2)->default(0);
            $table->decimal('total_deduction', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('taxable_income', 15, 2)->default(0);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_employee', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_employer', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan_employee', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan_employer', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
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
        Schema::dropIfExists('payslips');
    }
};
