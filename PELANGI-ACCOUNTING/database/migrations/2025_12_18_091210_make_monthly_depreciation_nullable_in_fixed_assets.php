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
        Schema::table('fixed_assets', function (Blueprint $table) {
            \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN monthly_depreciation DROP NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN monthly_depreciation SET NOT NULL');
            \DB::statement('UPDATE fixed_assets SET monthly_depreciation = 0 WHERE monthly_depreciation IS NULL');
        });
    }
};
