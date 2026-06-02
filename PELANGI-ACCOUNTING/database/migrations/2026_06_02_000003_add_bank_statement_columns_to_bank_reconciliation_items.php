<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop COA-based columns, add bank statement + matching columns
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['contra_account_id']);
            $table->dropColumn(['account_id', 'contra_account_id']);
        });

        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->string('type', 10)->nullable()->after('bank_reconciliation_id');
            $table->date('bank_date')->nullable()->after('type');
            $table->string('bank_description')->nullable()->after('bank_date');
            $table->decimal('bank_debit', 15, 2)->default(0)->after('debit');
            $table->decimal('bank_credit', 15, 2)->default(0)->after('bank_debit');
            $table->foreignId('suggested_invoice_id')->nullable()->after('bank_credit');
            $table->string('suggested_invoice_type')->nullable()->after('suggested_invoice_id');
            $table->decimal('suggested_invoice_amount', 15, 2)->nullable()->after('suggested_invoice_type');
            $table->string('match_status', 20)->default('unmatched')->after('suggested_invoice_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->dropColumn(['type', 'bank_date', 'bank_description', 'bank_debit', 'bank_credit', 'suggested_invoice_id', 'suggested_invoice_type', 'suggested_invoice_amount', 'match_status']);
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contra_account_id')->constrained('accounts')->cascadeOnDelete();
        });
    }
};
