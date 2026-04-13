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
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->decimal('other_costs', 15, 2)->default(0)->after('status');
            $table->foreignId('other_costs_account_id')->nullable()->after('other_costs');
        });

        Schema::table('payable_payments', function (Blueprint $table) {
            $table->decimal('other_costs', 15, 2)->default(0)->after('status');
            $table->foreignId('other_costs_account_id')->nullable()->after('other_costs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->dropColumn(['other_costs', 'other_costs_account_id']);
        });

        Schema::table('payable_payments', function (Blueprint $table) {
            $table->dropColumn(['other_costs', 'other_costs_account_id']);
        });
    }
};
