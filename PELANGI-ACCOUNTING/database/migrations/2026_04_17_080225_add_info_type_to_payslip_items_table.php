<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL stores Laravel enums as varchar + check constraint.
        // Drop the existing check constraint and add a new one that includes 'info'.
        DB::statement('ALTER TABLE payslip_items DROP CONSTRAINT IF EXISTS payslip_items_type_check');
        DB::statement("ALTER TABLE payslip_items ADD CONSTRAINT payslip_items_type_check CHECK (type IN ('allowance', 'deduction', 'info'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE payslip_items DROP CONSTRAINT IF EXISTS payslip_items_type_check');
        DB::statement("ALTER TABLE payslip_items ADD CONSTRAINT payslip_items_type_check CHECK (type IN ('allowance', 'deduction'))");
    }
};
