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
        // List of tables with description columns that need to be made nullable
        $tables = [
            'business_types',
            'payment_terms',
            'account_classifications',
            'units',
            'transaction_classifications',
            'tasks',
            'milestones',
            'advance_payments',
            'delivery_documents',
            'delivery_document_items',
            'sales_returns',
            'sales_return_items',
            'overpayment_refunds',
            'purchase_orders',
            'purchase_order_items',
            'goods_receipts',
            'goods_receipt_items',
            'purchase_invoices',
            'purchase_invoice_items',
            'purchase_returns',
            'purchase_return_items',
            'payable_payments',
            'overpayment_receipts',
            'check_disbursements',
            'cash_disbursements',
            'cash_disbursement_items',
            'advance_disbursements',
            'advance_disbursement_items',
            'cash_receipts',
            'cash_receipt_items',
            'advance_receipts',
            'advance_receipt_items',
            'cash_transfers',
            'inventory_transactions',
            'inventory_adjustments',
            'inventory_adjustment_items',
            'stock_opnames',
            'stock_opname_items',
            'warehouse_transfers',
            'warehouse_transfer_items',
            'fixed_asset_transactions',
            'fixed_asset_disposals',
            'opening_balances',
            'period_closings',
        ];

        foreach ($tables as $table) {
            // Check if table exists before trying to modify it
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    // Change description column to nullable
                    $table->text('description')->nullable()->change();
                });
            }
        }

        // Handle the account_classifications table specifically (it may be dropped)
        if (Schema::hasTable('account_classifications')) {
            Schema::table('account_classifications', function (Blueprint $table) {
                $table->text('description')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // List of tables to revert back to non-nullable
        $tables = [
            'business_types',
            'payment_terms',
            'account_classifications',
            'units',
            'transaction_classifications',
            'tasks',
            'milestones',
            'advance_payments',
            'delivery_documents',
            'delivery_document_items',
            'sales_returns',
            'sales_return_items',
            'overpayment_refunds',
            'purchase_orders',
            'purchase_order_items',
            'goods_receipts',
            'goods_receipt_items',
            'purchase_invoices',
            'purchase_invoice_items',
            'purchase_returns',
            'purchase_return_items',
            'payable_payments',
            'overpayment_receipts',
            'check_disbursements',
            'cash_disbursements',
            'cash_disbursement_items',
            'advance_disbursements',
            'advance_disbursement_items',
            'cash_receipts',
            'cash_receipt_items',
            'advance_receipts',
            'advance_receipt_items',
            'cash_transfers',
            'inventory_transactions',
            'inventory_adjustments',
            'inventory_adjustment_items',
            'stock_opnames',
            'stock_opname_items',
            'warehouse_transfers',
            'warehouse_transfer_items',
            'fixed_asset_transactions',
            'fixed_asset_disposals',
            'opening_balances',
            'period_closings',
        ];

        foreach ($tables as $table) {
            // Check if table exists before trying to modify it
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    // Revert description column to non-nullable
                    $table->text('description')->nullable(false)->change();
                });
            }
        }

        // Handle the account_classifications table specifically (it may be dropped)
        if (Schema::hasTable('account_classifications')) {
            Schema::table('account_classifications', function (Blueprint $table) {
                $table->text('description')->nullable(false)->change();
            });
        }
    }
};
