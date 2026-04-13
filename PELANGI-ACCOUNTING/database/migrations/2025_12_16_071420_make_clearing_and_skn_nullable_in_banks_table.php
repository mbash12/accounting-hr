<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('clearing_code', 20)->nullable()->change();
            $table->string('skn_code', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('clearing_code', 20)->nullable(false)->change();
            $table->string('skn_code', 20)->nullable(false)->change();
        });
    }
};
