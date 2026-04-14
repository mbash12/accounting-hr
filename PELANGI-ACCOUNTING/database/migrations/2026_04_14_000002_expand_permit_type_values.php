<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE permits DROP CONSTRAINT IF EXISTS permits_type_check');

            DB::unprepared(<<<'SQL'
DO $$
DECLARE constraint_name text;
BEGIN
    FOR constraint_name IN
        SELECT con.conname
        FROM pg_constraint con
        JOIN pg_class rel ON rel.oid = con.conrelid
        WHERE rel.relname = 'permits'
          AND con.contype = 'c'
          AND pg_get_constraintdef(con.oid) LIKE '%"type"%'
    LOOP
        EXECUTE format('ALTER TABLE permits DROP CONSTRAINT %I', constraint_name);
    END LOOP;
END $$;
SQL);

            DB::statement(<<<'SQL'
ALTER TABLE permits
ADD CONSTRAINT permits_type_check CHECK (
    "type" IN (
        'sick',
        'annual_leave',
        'unpaid_leave',
        'maternity_leave',
        'other_permit',
        'annual',
        'marry',
        'kids_marry',
        'khitan',
        'family_death',
        'maternity',
        'maternity_husband',
        'maternity_death',
        'force_majure',
        'nodn_sick',
        'sudden',
        'others'
    )
)
SQL);

            DB::statement("ALTER TABLE permits ALTER COLUMN \"type\" SET DEFAULT 'other_permit'");

            return;
        }

        Schema::table('permits', function (Blueprint $table) {
            $table->enum('type', [
                'sick',
                'annual_leave',
                'unpaid_leave',
                'maternity_leave',
                'other_permit',
                'annual',
                'marry',
                'kids_marry',
                'khitan',
                'family_death',
                'maternity',
                'maternity_husband',
                'maternity_death',
                'force_majure',
                'nodn_sick',
                'sudden',
                'others',
            ])->default('other_permit')->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE permits DROP CONSTRAINT IF EXISTS permits_type_check');

            DB::unprepared(<<<'SQL'
DO $$
DECLARE constraint_name text;
BEGIN
    FOR constraint_name IN
        SELECT con.conname
        FROM pg_constraint con
        JOIN pg_class rel ON rel.oid = con.conrelid
        WHERE rel.relname = 'permits'
          AND con.contype = 'c'
          AND pg_get_constraintdef(con.oid) LIKE '%"type"%'
    LOOP
        EXECUTE format('ALTER TABLE permits DROP CONSTRAINT %I', constraint_name);
    END LOOP;
END $$;
SQL);

            DB::statement(<<<'SQL'
ALTER TABLE permits
ADD CONSTRAINT permits_type_check CHECK (
    "type" IN ('sick', 'annual_leave', 'unpaid_leave', 'maternity_leave', 'other_permit')
)
SQL);

            DB::statement("ALTER TABLE permits ALTER COLUMN \"type\" SET DEFAULT 'other_permit'");

            return;
        }

        Schema::table('permits', function (Blueprint $table) {
            $table->enum('type', ['sick', 'annual_leave', 'unpaid_leave', 'maternity_leave', 'other_permit'])
                ->default('other_permit')
                ->change();
        });
    }
};
