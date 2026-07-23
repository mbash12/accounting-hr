<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repair migration: employees was marked as migrated but the table is missing.
     */
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('employee_id', 50)->unique();
            $table->string('nik', 16)->nullable();
            $table->string('npwp', 20)->nullable();
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
            $table->text('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE employees ADD COLUMN IF NOT EXISTS foto_vector vector(1408)');
        DB::statement('CREATE INDEX IF NOT EXISTS employees_foto_vector_idx ON employees USING hnsw (foto_vector vector_cosine_ops)');
    }

    public function down(): void
    {
        // Do not drop — this migration only repairs a missing table.
    }
};
