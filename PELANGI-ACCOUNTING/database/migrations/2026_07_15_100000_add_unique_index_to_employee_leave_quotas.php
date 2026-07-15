<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cleanup: hard-delete duplicate non-deleted records per (employee_id, year).
        //    Keep only the one with the lowest id among non-deleted records.
        //    Soft-deleted records are left alone (they don't conflict with the partial index).
        DB::statement("
            DELETE FROM employee_leave_quotas
            WHERE deleted_at IS NULL
              AND id NOT IN (
                SELECT min_id FROM (
                    SELECT MIN(id) AS min_id
                    FROM employee_leave_quotas
                    WHERE deleted_at IS NULL
                    GROUP BY employee_id, year
                ) AS keepers
              )
        ");

        // 2. Add partial unique index (PostgreSQL) — only enforces uniqueness
        //    for non-deleted records, so soft-deleted records don't conflict.
        DB::statement(
            'CREATE UNIQUE INDEX unique_employee_year_quota ' .
            'ON employee_leave_quotas (employee_id, year) ' .
            'WHERE deleted_at IS NULL'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS unique_employee_year_quota');
    }
};