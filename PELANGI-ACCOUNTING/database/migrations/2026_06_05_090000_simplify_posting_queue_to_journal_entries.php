<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        DB::statement("
            CREATE OR REPLACE VIEW posting_queue AS
            SELECT
                COALESCE(sub_module, 'journal_entry')::text AS type,
                entry_number::text AS document_number,
                date,
                COALESCE(reference_no, '')::text AS reference_no,
                COALESCE(description, '')::text AS description,
                amount::numeric(20,2) AS amount,
                status::text,
                id AS source_id,
                'App\\\\Models\\\\JournalEntry'::text AS source_type,
                company_id,
                created_at,
                updated_at
            FROM journal_entries
            WHERE is_posted = false
              AND deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        // Restore previous view - copy from the previous migration's up() method
        // The previous migration is at: database/migrations/2026_06_05_081710_remove_journal_entry_from_posting_queue_view.php
    }
};
