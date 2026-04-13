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
        Schema::table('accounts', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable()->change();
            $table->bigInteger('company_id')->nullable()->change();
            $table->bigInteger('created_by_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable(false)->change();
            $table->bigInteger('company_id')->nullable(false)->change();
            $table->bigInteger('created_by_user_id')->nullable(false)->change();
        });
    }
};
