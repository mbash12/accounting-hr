ALTER TABLE sales_orders DROP CONSTRAINT IF EXISTS sales_orders_status_check;
ALTER TABLE delivery_documents DROP CONSTRAINT IF EXISTS delivery_documents_status_check;
ALTER TABLE sales_invoices DROP CONSTRAINT IF EXISTS sales_invoices_status_check;
ALTER TABLE sales_returns DROP CONSTRAINT IF EXISTS sales_returns_status_check;
ALTER TABLE purchase_orders DROP CONSTRAINT IF EXISTS purchase_orders_status_check;
ALTER TABLE goods_receipts DROP CONSTRAINT IF EXISTS goods_receipts_status_check;
ALTER TABLE purchase_invoices DROP CONSTRAINT IF EXISTS purchase_invoices_status_check;
ALTER TABLE purchase_returns DROP CONSTRAINT IF EXISTS purchase_returns_status_check;
