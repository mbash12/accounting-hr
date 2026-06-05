<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        DB::statement("
            CREATE OR REPLACE VIEW posting_queue AS
            SELECT
                COALESCE(sub_module, 'journal_entry')::text AS type,
                entry_number::text AS document_number,
                date,
                COALESCE(reference_no, '')::text AS reference_no,
                COALESCE(description, '')::text AS description,
                amount::numeric(20,2) AS amount,
                status::text,
                id AS source_id,
                'App\\\\Models\\\\JournalEntry'::text AS source_type,
                company_id,
                created_at,
                updated_at
            FROM journal_entries
            WHERE is_posted = false
              AND deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS posting_queue");

        DB::statement("
            CREATE OR REPLACE VIEW posting_queue AS

            -- Standalone Journal Entries (draft, not from documents)
            SELECT
                'journal_entry'::text AS type,
                entry_number::text AS document_number,
                date,
                COALESCE(reference_no, '')::text AS reference_no,
                COALESCE(description, '')::text AS description,
                amount::numeric(20,2) AS amount,
                status::text,
                id AS source_id,
                'App\\\\Models\\\\JournalEntry'::text AS source_type,
                company_id,
                created_at,
                updated_at
            FROM journal_entries
            WHERE is_posted = false
              AND deleted_at IS NULL

            UNION ALL

            -- Cash Disbursements
            SELECT
                'cash_disbursement'::text AS type,
                disbursement_number::text AS document_number,
                date,
                COALESCE(reference_no, '')::text AS reference_no,
                COALESCE(description, '')::text AS description,
                total::numeric(20,2) AS amount,
                status::text,
                id AS source_id,
                'App\\\\Models\\\\CashDisbursement'::text AS source_type,
                company_id,
                created_at,
                updated_at
            FROM cash_disbursements
            WHERE status = 'draft'
              AND deleted_at IS NULL

            UNION ALL

            -- Cash Receipts
            SELECT
                'cash_receipt'::text,
                receipt_number::text,
                date,
                COALESCE(reference_no, '')::text,
                COALESCE(description, '')::text,
                total::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\CashReceipt'::text,
                company_id,
                created_at,
                updated_at
            FROM cash_receipts
            WHERE status = 'draft'
              AND deleted_at IS NULL

            UNION ALL

            -- Cash Transfers
            SELECT
                'cash_transfer'::text,
                transfer_number::text,
                date,
                COALESCE(reference_no, '')::text,
                COALESCE(description, '')::text,
                amount::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\CashTransfer'::text,
                company_id,
                created_at,
                updated_at
            FROM cash_transfers
            WHERE status = 'draft'
              AND deleted_at IS NULL

            UNION ALL

            -- Receivable Payments
            SELECT
                'receivable_payment'::text,
                payment_number::text,
                payment_date AS date,
                COALESCE(reference_no, '')::text,
                COALESCE(description, '')::text,
                total_payment::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\ReceivablePayment'::text,
                company_id,
                created_at,
                updated_at
            FROM receivable_payments
            WHERE status = 'draft'
              AND deleted_at IS NULL

            UNION ALL

            -- Payable Payments
            SELECT
                'payable_payment'::text,
                payment_number::text,
                payment_date AS date,
                COALESCE(reference_no, '')::text,
                COALESCE(description, '')::text,
                total_payment::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\PayablePayment'::text,
                company_id,
                created_at,
                updated_at
            FROM payable_payments
            WHERE status = 'draft'
              AND deleted_at IS NULL

            UNION ALL

            -- Sales Invoices
            SELECT
                'sales_invoice'::text,
                invoice_number::text,
                date,
                COALESCE(reference_no, '')::text,
                ''::text AS description,
                total_amount::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\SalesInvoice'::text,
                company_id,
                created_at,
                updated_at
            FROM sales_invoices
            WHERE status != 'posted'
              AND deleted_at IS NULL

            UNION ALL

            -- Sales Returns
            SELECT
                'sales_return'::text,
                return_number::text,
                date,
                COALESCE(reference_no, '')::text,
                ''::text AS description,
                0::numeric(20,2) AS amount,
                status::text,
                id,
                'App\\\\Models\\\\SalesReturn'::text,
                company_id,
                created_at,
                updated_at
            FROM sales_returns
            WHERE status != 'posted'
              AND deleted_at IS NULL

            UNION ALL

            -- Goods Receipts
            SELECT
                'goods_receipt'::text,
                receipt_number::text,
                date,
                ''::text AS reference_no,
                ''::text AS description,
                0::numeric(20,2) AS amount,
                status::text,
                id,
                'App\\\\Models\\\\GoodsReceipt'::text,
                company_id,
                created_at,
                updated_at
            FROM goods_receipts
            WHERE status != 'posted'
              AND deleted_at IS NULL

            UNION ALL

            -- Purchase Invoices
            SELECT
                'purchase_invoice'::text,
                invoice_number::text,
                date,
                ''::text AS reference_no,
                ''::text AS description,
                total::numeric(20,2),
                status::text,
                id,
                'App\\\\Models\\\\PurchaseInvoice'::text,
                company_id,
                created_at,
                updated_at
            FROM purchase_invoices
            WHERE status != 'posted'
              AND deleted_at IS NULL

            UNION ALL

            -- Purchase Returns
            SELECT
                'purchase_return'::text,
                return_number::text,
                date,
                ''::text AS reference_no,
                ''::text AS description,
                0::numeric(20,2) AS amount,
                status::text,
                id,
                'App\\\\Models\\\\PurchaseReturn'::text,
                company_id,
                created_at,
                updated_at
            FROM purchase_returns
            WHERE status != 'posted'
              AND deleted_at IS NULL
        ");
    }
};
