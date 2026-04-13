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
        Schema::table('cash_disbursements', function (Blueprint $table) {
            $table->string('disbursement_number', 50)->nullable()->after('reference_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_disbursements', function (Blueprint $table) {
            $table->dropColumn('disbursement_number');
        });
    }
};
