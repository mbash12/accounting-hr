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
        Schema::table('cash_receipts', function (Blueprint $table) {
            $table->string('sub_module')->default('pemasukan_kas')->after('status');
            $table->string('reference_type')->nullable()->after('sub_module');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->foreignId('cash_bank_transaction_id')->nullable()->after('reference_id');
        });

        Schema::table('cash_disbursements', function (Blueprint $table) {
            $table->string('sub_module')->default('pengeluaran')->after('status');
            $table->string('reference_type')->nullable()->after('sub_module');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->foreignId('cash_bank_transaction_id')->nullable()->after('reference_id');
        });

        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->string('sub_module')->default('transfer_kas')->after('status');
            $table->string('reference_type')->nullable()->after('sub_module');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->foreignId('cash_bank_transaction_id')->nullable()->after('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_receipts', function (Blueprint $table) {
            $table->dropColumn(['sub_module', 'reference_type', 'reference_id', 'cash_bank_transaction_id']);
        });

        Schema::table('cash_disbursements', function (Blueprint $table) {
            $table->dropColumn(['sub_module', 'reference_type', 'reference_id', 'cash_bank_transaction_id']);
        });

        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->dropColumn(['sub_module', 'reference_type', 'reference_id', 'cash_bank_transaction_id']);
        });
    }
};











