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
        Schema::create('period_closings', function (Blueprint $table) {
            $table->id();
            $table->enum('period_type', ["daily","weekly","monthly","quarterly","yearly"]);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ["pending","in_progress","completed","failed"]);
            $table->timestamp('closed_at');
            $table->text('description');
            $table->foreignId('closed_by_user_id');
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
        Schema::dropIfExists('period_closings');
    }
};
