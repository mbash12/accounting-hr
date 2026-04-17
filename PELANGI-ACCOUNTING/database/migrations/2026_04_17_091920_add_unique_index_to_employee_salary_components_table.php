<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_salary_components', function (Blueprint $table) {
            $table->unique(['employee_id', 'salary_component_id'], 'uq_employee_salary_component');
        });
    }

    public function down(): void
    {
        Schema::table('employee_salary_components', function (Blueprint $table) {
            $table->dropUnique('uq_employee_salary_component');
        });
    }
};
