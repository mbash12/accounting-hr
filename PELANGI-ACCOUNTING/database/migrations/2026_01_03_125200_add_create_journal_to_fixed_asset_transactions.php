<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_asset_transactions', function (Blueprint $table) {
            $table->boolean('create_journal')->default(false)->after('transaction_type');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_asset_transactions', function (Blueprint $table) {
            $table->dropColumn('create_journal');
        });
    }
};
