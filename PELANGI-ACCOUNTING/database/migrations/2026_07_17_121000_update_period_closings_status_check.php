<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE period_closings DROP CONSTRAINT IF EXISTS period_closings_status_check');

        DB::table('period_closings')
            ->whereIn('status', ['completed', 'in_progress'])
            ->update(['status' => 'closed']);

        DB::table('period_closings')
            ->whereIn('status', ['pending', 'failed'])
            ->update(['status' => 'open']);

        DB::statement("ALTER TABLE period_closings ADD CONSTRAINT period_closings_status_check CHECK (status::text = ANY (ARRAY['open'::text, 'closed'::text]))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE period_closings DROP CONSTRAINT IF EXISTS period_closings_status_check');
        DB::statement("ALTER TABLE period_closings ADD CONSTRAINT period_closings_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'in_progress'::text, 'completed'::text, 'failed'::text]))");
    }
};
