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
            // Make all account IDs nullable
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN sales_account_id DROP NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN asset_account_id DROP NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN accumulated_depreciation_account_id DROP NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN depreciation_account_id DROP NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_asset_categories', function (Blueprint $table) {
            // Revert to non-nullable (with defaults if needed for existing NULL values)
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN sales_account_id SET NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN asset_account_id SET NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN accumulated_depreciation_account_id SET NOT NULL');
            \DB::statement('ALTER TABLE fixed_asset_categories ALTER COLUMN depreciation_account_id SET NOT NULL');
        });
    }
};