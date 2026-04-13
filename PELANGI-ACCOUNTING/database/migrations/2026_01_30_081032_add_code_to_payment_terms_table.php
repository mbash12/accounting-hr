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
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->after('id');
        });

        // Add unique index for code + company_id combination
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->unique(['code', 'company_id'], 'payment_terms_code_company_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->dropUnique('payment_terms_code_company_unique');
            $table->dropColumn('code');
        });
    }
};
