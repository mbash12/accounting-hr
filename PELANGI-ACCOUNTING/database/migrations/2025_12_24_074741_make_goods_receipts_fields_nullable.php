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
        Schema::table('goods_receipts', function (Blueprint $table) {
            // Skip enum column, handle it separately
            $table->date('date')->nullable()->change();
            $table->string('reference_no')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->foreignId('supplier_id')->nullable()->change();
            $table->foreignId('purchase_order_id')->nullable()->change();
            $table->foreignId('job_id')->nullable()->change();
            $table->foreignId('company_id')->nullable()->change();
            $table->foreignId('created_by_user_id')->nullable()->change();
            $table->foreignId('updated_by_user_id')->nullable()->change();
        });
        
        // Handle status column separately for PostgreSQL
        DB::statement('ALTER TABLE goods_receipts ALTER COLUMN status TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE goods_receipts ALTER COLUMN status DROP NOT NULL');
        DB::statement('ALTER TABLE goods_receipts ALTER COLUMN status DROP DEFAULT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->string('reference_no')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->foreignId('supplier_id')->nullable(false)->change();
            $table->foreignId('purchase_order_id')->nullable(false)->change();
            $table->foreignId('job_id')->nullable(false)->change();
            $table->foreignId('company_id')->nullable(false)->change();
            $table->foreignId('created_by_user_id')->nullable(false)->change();
            $table->foreignId('updated_by_user_id')->nullable(false)->change();
        });
        
        // Revert status column to enum with check constraint
        DB::statement("ALTER TABLE goods_receipts ALTER COLUMN status TYPE VARCHAR(255) CHECK (status IN ('pending', 'received', 'inspected', 'approved', 'rejected', 'cancelled'))");
        DB::statement('ALTER TABLE goods_receipts ALTER COLUMN status SET NOT NULL');
    }
};
