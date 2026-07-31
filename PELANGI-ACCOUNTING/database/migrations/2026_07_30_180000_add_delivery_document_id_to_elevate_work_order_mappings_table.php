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
        Schema::table('elevate_work_order_mappings', function (Blueprint $table) {
            $table->foreignId('delivery_document_id')
                ->nullable()
                ->after('sales_invoice_id')
                ->constrained('delivery_documents')
                ->nullOnDelete()
                ->comment('Generated sales delivery document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elevate_work_order_mappings', function (Blueprint $table) {
            $table->dropForeign(['delivery_document_id']);
            $table->dropColumn('delivery_document_id');
        });
    }
};
