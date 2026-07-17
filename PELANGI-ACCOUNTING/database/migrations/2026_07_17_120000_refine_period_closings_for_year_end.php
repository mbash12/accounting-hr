<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('period_closings', function (Blueprint $table) {
            $table->timestamp('closed_at')->nullable()->change();
            $table->foreignId('closed_by_user_id')->nullable()->change();
            $table->timestamp('reopened_at')->nullable()->after('closed_at');
            $table->foreignId('reopened_by_user_id')->nullable()->after('reopened_at');
            $table->text('reopen_reason')->nullable()->after('reopened_by_user_id');
            $table->foreignId('closing_journal_entry_id')->nullable()->after('reopen_reason');
        });

        // Normalize legacy status values to open|closed
        DB::table('period_closings')
            ->whereIn('status', ['completed', 'in_progress'])
            ->update(['status' => 'closed']);

        DB::table('period_closings')
            ->whereIn('status', ['pending', 'failed'])
            ->update(['status' => 'open']);

        Schema::table('period_closings', function (Blueprint $table) {
            $table->unique(
                ['company_id', 'period_type', 'start_date', 'end_date'],
                'period_closings_company_type_range_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('period_closings', function (Blueprint $table) {
            $table->dropUnique('period_closings_company_type_range_unique');
            $table->dropColumn([
                'reopened_at',
                'reopened_by_user_id',
                'reopen_reason',
                'closing_journal_entry_id',
            ]);
        });
    }
};
