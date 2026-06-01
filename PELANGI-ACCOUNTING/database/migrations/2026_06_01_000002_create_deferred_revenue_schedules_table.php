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
        Schema::create('deferred_revenue_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('period_number');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('planned_amount', 15, 2);
            $table->decimal('recognized_amount', 15, 2)->default(0);
            $table->date('recognized_date')->nullable();
            $table->string('status', 20)->default('pending'); // pending, recognized, reversed
            $table->text('notes')->nullable();
            $table->foreignId('deferred_revenue_id');
            $table->foreignId('journal_entry_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('deferred_revenue_id')->references('id')->on('deferred_revenues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deferred_revenue_schedules');
    }
};
