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
        \DB::statement("ALTER TABLE cash_disbursements DROP CONSTRAINT IF EXISTS cash_disbursements_status_check");
        \DB::statement("ALTER TABLE cash_disbursements ADD CONSTRAINT cash_disbursements_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text, ('posted'::character varying)::text])))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE cash_disbursements DROP CONSTRAINT IF EXISTS cash_disbursements_status_check");
        \DB::statement("ALTER TABLE cash_disbursements ADD CONSTRAINT cash_disbursements_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))");
    }
};
