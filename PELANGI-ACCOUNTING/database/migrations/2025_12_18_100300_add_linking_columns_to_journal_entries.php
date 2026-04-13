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
        Schema::table('journal_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('journal_entries', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->nullable()->after('amount');
            }

            if (!Schema::hasColumn('journal_entries', 'is_posted')) {
                $table->boolean('is_posted')->default(false)->after('status');
            }

            $table->string('sub_module')->nullable()->after('is_posted');
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
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropColumn([
                'total_amount',
                'is_posted',
                'sub_module',
                'reference_type',
                'reference_id',
                'cash_bank_transaction_id',
            ]);
        });
    }
};











