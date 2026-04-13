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
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->string('return_number')->nullable()->after('reference_no');
            $table->string('reference_no')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->foreignId('purchase_invoice_id')->nullable()->change();
            $table->foreignId('job_id')->nullable()->change();
            $table->foreignId('updated_by_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropColumn('return_number');
            $table->string('reference_no')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->foreignId('purchase_invoice_id')->nullable(false)->change();
            $table->foreignId('job_id')->nullable(false)->change();
            $table->foreignId('updated_by_user_id')->nullable(false)->change();
        });
    }
};
