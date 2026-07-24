<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('code', 10);                          // "R", "0", "1", "2", "3", "RS1", "RS2", "OFF"
            $table->string('name', 100);                         // "REGULER", "PAGI (1)", etc.
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('color', 20)->default('#cfe2ff');     // cell background colour
            $table->string('text_color', 20)->default('#000000');
            $table->boolean('is_off')->default(false);           // true for OFF / 0 codes
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreignId('created_by_user_id')->nullable();

            $table->unique(['company_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_types');
    }
};
