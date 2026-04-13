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
        Schema::table('journal_entries', function (Blueprint $table) {

            if (!Schema::hasColumn('journal_entries', 'is_posted')) {
                $table->boolean('is_posted')->nullable()->after('status');
            }
            $table->unsignedBigInteger('posted_by_user_id')->nullable()->change();
            $table->unsignedBigInteger('updated_by_user_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
             if (Schema::hasColumn('journal_entries', 'is_posted')) {
                $table->dropColumn('is_posted');
            }
        });
    }
};
