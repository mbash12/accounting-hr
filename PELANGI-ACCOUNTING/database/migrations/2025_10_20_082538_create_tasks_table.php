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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number', 50)->unique();
            $table->enum('task_type', ["milestone","deliverable","issue","bug_fix","feature","review"]);
            $table->string('title', 200);
            $table->text('description');
            $table->date('due_date')->nullable();
            $table->enum('status', ["todo","in_progress","review","completed","cancelled"]);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('assigned_to_id');
            $table->foreignId('job_id');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
