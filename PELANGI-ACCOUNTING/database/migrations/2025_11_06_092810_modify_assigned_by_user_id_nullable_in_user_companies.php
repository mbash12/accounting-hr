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
        Schema::table('user_companies', function (Blueprint $table) {
            // Make assigned_by_user_id nullable to prevent the error
            $table->foreignId('assigned_by_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_companies', function (Blueprint $table) {
            $table->foreignId('assigned_by_user_id')->nullable(false)->change();
        });
    }
};
