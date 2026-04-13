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
            $table->text('transaction_notes')->nullable()->after('description');
        });

        Schema::table('payable_payments', function (Blueprint $table) {
            $table->text('transaction_notes')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_notes');
        });

        Schema::table('payable_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_notes');
        });
    }
};
