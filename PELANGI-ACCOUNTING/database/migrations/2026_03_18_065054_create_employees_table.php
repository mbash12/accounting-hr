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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('employee_id', 50)->unique();
            $table->string('nik', 16)->nullable(); // National ID
            $table->string('npwp', 20)->nullable(); // Tax ID
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('position', 100)->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['permanent', 'contract', 'internship', 'probation'])->default('probation');
            $table->enum('ptkp_status', ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3', 'KI/0', 'KI/1', 'KI/2', 'KI/3'])->default('TK/0');
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_holder', 200)->nullable();
            $table->string('bpjs_kesehatan_number', 50)->nullable();
            $table->string('bpjs_ketenagakerjaan_number', 50)->nullable();
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('employees');
    }
};
