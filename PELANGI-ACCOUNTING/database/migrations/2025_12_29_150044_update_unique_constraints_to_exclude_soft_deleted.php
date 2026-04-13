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
        DB::statement('DROP INDEX IF EXISTS cash_receipts_receipt_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_disbursements_disbursement_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_transfers_transfer_number_unique');
        
        DB::statement('CREATE UNIQUE INDEX cash_receipts_receipt_number_unique ON cash_receipts (receipt_number) WHERE receipt_number IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX cash_disbursements_disbursement_number_unique ON cash_disbursements (disbursement_number) WHERE disbursement_number IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX cash_transfers_transfer_number_unique ON cash_transfers (transfer_number) WHERE transfer_number IS NOT NULL AND deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS cash_receipts_receipt_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_disbursements_disbursement_number_unique');
        DB::statement('DROP INDEX IF EXISTS cash_transfers_transfer_number_unique');
        
        DB::statement('CREATE UNIQUE INDEX cash_receipts_receipt_number_unique ON cash_receipts (receipt_number) WHERE receipt_number IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX cash_disbursements_disbursement_number_unique ON cash_disbursements (disbursement_number) WHERE disbursement_number IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX cash_transfers_transfer_number_unique ON cash_transfers (transfer_number) WHERE transfer_number IS NOT NULL');
    }
};
