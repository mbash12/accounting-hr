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
        // For PostgreSQL, we need to use raw SQL to modify enum columns
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN depreciation_method DROP NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN useful_life DROP NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN department_id DROP NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN transaction_in_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN depreciation_method SET NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN useful_life SET NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN department_id SET NOT NULL');
        \DB::statement('ALTER TABLE fixed_assets ALTER COLUMN transaction_in_id SET NOT NULL');
    }
};
