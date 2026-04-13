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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('milestone_number', 50)->unique();
            $table->enum('milestone_type', ["phase","deliverable","payment","review","acceptance"]);
            $table->string('title', 200);
            $table->text('description');
            $table->date('target_date');
            $table->date('actual_date')->nullable();
            $table->json('pending_history')->nullable();
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
        Schema::dropIfExists('milestones');
    }
};
