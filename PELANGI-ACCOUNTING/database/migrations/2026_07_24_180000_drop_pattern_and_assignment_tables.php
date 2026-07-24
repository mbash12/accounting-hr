<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pattern/assignment tables were removed; they are no longer used.
        // Schedule is uploaded directly per (employee, date) instead.
        Schema::dropIfExists('shift_patterns');
        Schema::dropIfExists('employee_shift_assignments');

        // Drop the dangling FK + column on shift_schedules.
        if (Schema::hasColumn('shift_schedules', 'shift_assignment_id')) {
            Schema::table('shift_schedules', function (Blueprint $table) {
                $table->dropForeign(['shift_assignment_id']);
                $table->dropColumn('shift_assignment_id');
            });
        }
    }

    public function down(): void
    {
        // Recreate shift_patterns
        Schema::create('shift_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('cycle_length');
            $table->jsonb('sequence');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Recreate employee_shift_assignments
        Schema::create('employee_shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_pattern_id')->constrained('shift_patterns')->cascadeOnDelete();
            $table->date('start_date');
            $table->unsignedSmallInteger('offset_days')->default(0);
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['employee_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });

        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->foreignId('shift_assignment_id')->nullable()->after('shift_type_id')->constrained('employee_shift_assignments')->nullOnDelete();
        });
    }
};
