<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->foreignId('unit_category_id')->nullable()->after('is_active')->constrained('unit_categories')->nullOnDelete();
            $table->decimal('conversion_factor', 15, 6)->default(1)->after('unit_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['unit_category_id']);
            $table->dropColumn(['unit_category_id', 'conversion_factor']);
        });
    }
};
