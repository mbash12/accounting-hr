<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->string('name', 200);
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedInteger('radius_meters');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_spots');
    }
};
