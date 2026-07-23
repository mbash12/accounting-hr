<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing simple unique constraint/index if it exists
        try {
            DB::statement('ALTER TABLE cash_receipts DROP CONSTRAINT IF EXISTS cash_receipts_receipt_number_unique');
        } catch (\Exception $e) {
            // Constraint might not exist or have a different name
        }

        try {
            DB::statement('DROP INDEX IF EXISTS cash_receipts_receipt_number_unique');
        } catch (\Exception $e) {
            // Index might not exist
        }

        // Resolve duplicates across companies before adding the scoped index:
        // keep the first record per (company_id, receipt_number), null out the rest.
        DB::statement('
            UPDATE cash_receipts cr1
            SET receipt_number = NULL
            WHERE cr1.receipt_number IS NOT NULL
            AND cr1.id NOT IN (
                SELECT MIN(cr2.id)
                FROM cash_receipts cr2
                WHERE cr2.receipt_number IS NOT NULL
                GROUP BY cr2.company_id, cr2.receipt_number
            )
        ');

        // Create a company-scoped unique constraint
        // This allows the same receipt number across different companies
        DB::statement('
            CREATE UNIQUE INDEX cash_receipts_company_receipt_number_unique
            ON cash_receipts (company_id, receipt_number)
            WHERE receipt_number IS NOT NULL AND deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the company-scoped unique index
        DB::statement('DROP INDEX IF EXISTS cash_receipts_company_receipt_number_unique');

        // Recreate the simple unique constraint (original state)
        DB::statement('
            CREATE UNIQUE INDEX cash_receipts_receipt_number_unique
            ON cash_receipts (receipt_number)
            WHERE receipt_number IS NOT NULL AND deleted_at IS NULL
        ');
    }
};
