<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetPostgresSequences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-pk-sequences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset PostgreSQL primary key sequences to max(id) + 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('This command is only for PostgreSQL databases.');
            return 1;
        }

        $this->info('Resetting PostgreSQL sequences...');

        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename NOT LIKE '%_seq'");

        foreach ($tables as $table) {
            $tableName = $table->tablename;

            // Check if table has an 'id' column
            $hasId = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = ? AND column_name = 'id'", [$tableName]);

            if (!empty($hasId)) {
                $sequenceName = "{$tableName}_id_seq";
                
                // Check if the sequence exists
                $hasSequence = DB::select("SELECT sequence_name FROM information_schema.sequences WHERE sequence_name = ?", [$sequenceName]);

                if (!empty($hasSequence)) {
                    $maxId = DB::select("SELECT MAX(id) as max_id FROM \"{$tableName}\"")[0]->max_id;
                    $nextId = ($maxId ?? 0) + 1;

                    DB::statement("SELECT setval(?, ?)", [$sequenceName, $nextId]);
                    $this->line("Reset sequence for <comment>{$tableName}</comment> to <info>{$nextId}</info>");
                }
            }
        }

        $this->info('All sequences reset successfully.');
        return 0;
    }
}
