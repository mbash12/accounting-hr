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
            $table->string('code', 50)->nullable()->after('name');

            // Add unique constraint for code within company scope
            $table->unique(['code', 'company_id'], 'fixed_asset_categories_code_company_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_asset_categories', function (Blueprint $table) {
            $table->dropUnique('fixed_asset_categories_code_company_unique');
            $table->dropColumn('code');
        });
    }
};
