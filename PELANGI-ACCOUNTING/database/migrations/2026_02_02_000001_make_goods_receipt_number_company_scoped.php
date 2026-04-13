<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing simple unique constraint if it exists
        try {
            DB::statement('ALTER TABLE goods_receipts DROP CONSTRAINT IF EXISTS goods_receipts_receipt_number_unique');
        } catch (\Exception $e) {
            // Constraint might not exist or have a different name
        }
        
        try {
            DB::statement('DROP INDEX IF EXISTS goods_receipts_receipt_number_unique');
        } catch (\Exception $e) {
            // Index might not exist
        }
        
        // Create a company-scoped unique constraint
        // This allows the same receipt number across different companies
        DB::statement('
            CREATE UNIQUE INDEX goods_receipts_company_receipt_number_unique 
            ON goods_receipts (company_id, receipt_number) 
            WHERE receipt_number IS NOT NULL AND deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the company-scoped unique index
        DB::statement('DROP INDEX IF EXISTS goods_receipts_company_receipt_number_unique');
        
        // Recreate the simple unique constraint (original state)
        DB::statement('
            CREATE UNIQUE INDEX goods_receipts_receipt_number_unique 
            ON goods_receipts (receipt_number) 
            WHERE receipt_number IS NOT NULL AND deleted_at IS NULL
        ');
    }
};