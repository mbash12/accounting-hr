<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->string('reference_no', 100)->nullable()->after('bank_description');
            $table->string('account_code', 50)->nullable()->after('reference_no');
        });
    }

    public function down(): void
    {
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->dropColumn(['reference_no', 'account_code']);
        });
    }
};
