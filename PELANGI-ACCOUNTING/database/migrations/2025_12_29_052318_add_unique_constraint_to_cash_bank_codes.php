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
        DB::statement('
            UPDATE cash_receipts cr1
            SET receipt_number = NULL
            WHERE cr1.id NOT IN (
                SELECT MIN(cr2.id)
                FROM cash_receipts cr2
                WHERE cr2.receipt_number IS NOT NULL
                GROUP BY cr2.receipt_number
            )
            AND cr1.receipt_number IS NOT NULL
        ');
        
        DB::statement('
            UPDATE cash_disbursements cd1
            SET disbursement_number = NULL
            WHERE cd1.id NOT IN (
                SELECT MIN(cd2.id)
                FROM cash_disbursements cd2
                WHERE cd2.disbursement_number IS NOT NULL
                GROUP BY cd2.disbursement_number
            )
            AND cd1.disbursement_number IS NOT NULL
        ');
        
        DB::statement('
            UPDATE cash_transfers ct1
            SET transfer_number = NULL
            WHERE ct1.id NOT IN (
                SELECT MIN(ct2.id)
                FROM cash_transfers ct2
                WHERE ct2.transfer_number IS NOT NULL
                GROUP BY ct2.transfer_number
            )
            AND ct1.transfer_number IS NOT NULL
        ');
        
        DB::statement('CREATE UNIQUE INDEX cash_receipts_receipt_number_unique ON cash_receipts (receipt_number) WHERE receipt_number IS NOT NULL');
        
        DB::statement('CREATE UNIQUE INDEX cash_disbursements_disbursement_number_unique ON cash_disbursements (disbursement_number) WHERE disbursement_number IS NOT NULL');
        
        DB::statement('CREATE UNIQUE INDEX cash_transfers_transfer_number_unique ON cash_transfers (transfer_number) WHERE transfer_number IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop unique indexes
        DB::statement('DROP INDEX IF EXISTS cash_receipts_receipt_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_disbursements_disbursement_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_transfers_transfer_number_unique');
    }
};
