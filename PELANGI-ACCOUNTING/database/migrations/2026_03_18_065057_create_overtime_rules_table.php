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
        Schema::create('overtime_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('is_default')->default(false);
            $table->decimal('base_hourly_rate_divisor', 8, 2)->default(173.00);
            $table->decimal('workday_first_hour_multiplier', 4, 2)->default(1.50);
            $table->decimal('workday_subsequent_hour_multiplier', 4, 2)->default(2.00);
            $table->decimal('holiday_multiplier', 4, 2)->default(2.00);
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
        Schema::dropIfExists('overtime_rules');
    }
};
