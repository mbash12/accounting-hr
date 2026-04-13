<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('journal_entry_items', function (Blueprint $table) {
            $table->foreignId('cost_center_id')->nullable()->change();
        });

        DB::statement('ALTER TABLE journal_entry_items ALTER COLUMN cost_center_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entry_items', function (Blueprint $table) {
            $table->foreignId('cost_center_id')->nullable(false)->change();
        });

        DB::statement('ALTER TABLE journal_entry_items ALTER COLUMN cost_center_id SET NOT NULL');
    }
};
