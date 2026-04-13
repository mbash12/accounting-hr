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
        Schema::create('fixed_asset_depreciations', function (Blueprint $table) {
            $table->id();
            $table->integer('year_number');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('months_count');
            $table->string('beginning_book_value');
            $table->decimal('percentage', 5, 2);
            $table->string('yearly_depreciation');
            $table->string('monthly_depreciation');
            $table->string('ending_book_value');
            $table->foreignId('fixed_asset_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_depreciations');
    }
};
