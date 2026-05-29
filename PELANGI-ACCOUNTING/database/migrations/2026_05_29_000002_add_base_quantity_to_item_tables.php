<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $itemTables = [
        'purchase_order_items',
        'purchase_invoice_items',
        'purchase_return_items',
        'goods_receipt_items',
        'sales_order_items',
        'sales_invoice_items',
        'sales_return_items',
        'delivery_document_items',
        'inventory_adjustment_items',
        'warehouse_transfer_items',
        'stock_opname_items',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->itemTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->decimal('base_quantity', 15, 2)->nullable()->after('quantity');
                $table->decimal('conversion_factor', 15, 6)->default(1)->after('base_quantity');
            });
        }

        // Backfill existing records: base_quantity = quantity, conversion_factor = 1
        // Use CAST to handle tables where quantity might still be a string type
        foreach ($this->itemTables as $tableName) {
            $qtyCol = $tableName === 'delivery_document_items' ? 'total_quantity' : 'quantity';
            DB::table($tableName)
                ->whereNull('base_quantity')
                ->update([
                    'base_quantity' => DB::raw("CAST(\"{$qtyCol}\" AS DECIMAL(15,2))"),
                    'conversion_factor' => 1,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->itemTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['base_quantity', 'conversion_factor']);
            });
        }
    }
};
