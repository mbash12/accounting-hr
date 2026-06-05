<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreignId('coa_account_id')->nullable()->after('account_name')->constrained('accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['coa_account_id']);
            $table->dropColumn('coa_account_id');
        });
    }
};
