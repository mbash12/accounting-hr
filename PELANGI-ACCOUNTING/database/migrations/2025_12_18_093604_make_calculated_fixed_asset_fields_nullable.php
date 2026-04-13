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
        // Make accumulated_depreciation nullable as it's calculated over time
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN accumulated_depreciation DROP NOT NULL');
        // Make book_value nullable as it's calculated from acquisition_value - accumulated_depreciation
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN book_value DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set accumulated_depreciation back to not null with default
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN accumulated_depreciation SET NOT NULL');
        \DB::statement('UPDATE fixed_assets SET accumulated_depreciation = 0 WHERE accumulated_depreciation IS NULL');
        // Set book_value back to not null with default
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN book_value SET NOT NULL');
        \DB::statement('UPDATE fixed_assets SET book_value = acquisition_value WHERE book_value IS NULL');
    }
};
