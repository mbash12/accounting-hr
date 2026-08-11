<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Synchronize the PostgreSQL sequence after a database restore/import.
     *
     * Restoring rows with explicit IDs does not advance PostgreSQL's sequence,
     * which makes the next insert attempt to reuse an existing primary key.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql' || ! Schema::hasTable('account_mappings')) {
            return;
        }

        $sequence = DB::selectOne(
            'SELECT pg_get_serial_sequence(?, ?) AS sequence_name',
            ['account_mappings', 'id'],
        )?->sequence_name;

        if (! $sequence) {
            return;
        }

        $maxId = DB::table('account_mappings')->max('id') ?? 1;
        $hasRecords = DB::table('account_mappings')->exists();

        DB::statement('SELECT setval(?::regclass, ?::bigint, ?::boolean)', [
            $sequence,
            $maxId,
            $hasRecords ? 'true' : 'false',
        ]);
    }

    /**
     * Sequence positions are intentionally not rolled back.
     */
    public function down(): void
    {
        // No-op.
    }
};
