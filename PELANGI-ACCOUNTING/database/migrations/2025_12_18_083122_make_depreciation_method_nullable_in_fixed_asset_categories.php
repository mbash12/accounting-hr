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
        Schema::table('fixed_asset_categories', function (Blueprint $table) {
            // Drop the check constraint first
            \DB::statement('ALTER TABLE fixed_asset_categories DROP CONSTRAINT IF EXISTS fixed_asset_categories_depreciation_method_check');
            // Modify the column to make it nullable
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN depreciation_method DROP NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_asset_categories', function (Blueprint $table) {
            // Set the column back to not nullable
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN depreciation_method SET NOT NULL');
            // Recreate the check constraint with the original values
            \DB::statement("ALTER TABLE fixed_asset_categories ADD CONSTRAINT fixed_asset_categories_depreciation_method_check CHECK (depreciation_method = ANY (ARRAY['straight_line'::text, 'declining_balance'::text, 'double_declining'::text, 'sum_of_years'::text, 'units_of_production'::text]))");
        });
    }
};
