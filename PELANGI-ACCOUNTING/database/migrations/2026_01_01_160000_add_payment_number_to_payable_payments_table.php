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
        Schema::table('payable_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payable_payments', 'payment_number')) {
                $table->string('payment_number', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('payable_payments', 'is_incoming_giro')) {
                $table->boolean('is_incoming_giro')->default(false)->after('bank_account_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payable_payments', function (Blueprint $table) {
            if (Schema::hasColumn('payable_payments', 'payment_number')) {
                $table->dropColumn('payment_number');
            }
            if (Schema::hasColumn('payable_payments', 'is_incoming_giro')) {
                $table->dropColumn('is_incoming_giro');
            }
        });
    }
};


