# Genericization Progress Tracker

**Branch:** `feat/genericize-accounting`
**Started:** 2026-05-21

## Completed

- [x] **Task 1: Remove Sync Monitoring Module** (commit `5ed3713`)
  - Deleted: InventorySyncService, SyncInvoiceToInventoryJob, InvoiceSyncJob, SalesInvoiceObserver, SyncInvoicesToInventory, InvoiceSyncController, SyncMonitoring page + view
  - Removed: observer registration, page registration, API/web routes, sync fields from SalesInvoice model
  - Migration: `2026_05_21_000002_drop_sync_tables.php` (drops invoice_sync_jobs + sync columns)

- [x] **Task 2: Drop Pelangi Business Columns** (commit `d68f54c`)
  - Migration: `2026_05_21_000001_drop_pelangi_columns.php` (already run)
  - Dropped: order_type, related_order_id, job_number, jb_job_number, client_po_number, advance_payment_id, job_id from sales_orders
  - Dropped: item_name, is_production from sales_order_items
  - Dropped: order_type, related_order_id, advance_payment_id, department_id, sales_order_id, job_id from purchase_orders
  - Dropped: item_name from purchase_order_items
  - Dropped: supplier_id, min_order_qty from products
  - Dropped: settings from companies

- [x] **Task 3: Clean SalesOrder Model and Resources** (commit `242d664`)
  - SalesOrder model: removed fillable/casts/relationships for deleted columns
  - SalesOrderItem model: removed item_name, is_production
  - SalesOrderResource: removed isLocked, ViewSalesOrderDetail
  - SalesOrderForm: removed order_type, job fields, item_name, is_production; English labels
  - SalesOrdersTable: removed order_type/job columns, createPO action; English labels
  - EditSalesOrder: removed isLocked logic
  - ListSalesOrders: removed isLocked logic
  - Deleted: ViewSalesOrderDetail.php, sales-order-detail-view.blade.php

- [x] **Task 4: Clean PurchaseOrder Model and Resources**
  - PurchaseOrder model: removed order_type, sales_order_id, job_id, related_order_id, advance_payment_id, department_id from fillable/casts; removed salesOrder, job, department, relatedOrder, advancePayment relationships; English notification text
  - PurchaseOrderItem model: removed item_name from fillable
  - PurchaseOrderForm: removed sales_order_id dropdown + auto-fill, department_id field, item_name field + auto-fill, order_type hidden field; all English labels
  - PurchaseOrdersTable: removed salesOrder columns, department column, job_id in createGoodsReceipt/createPurchaseInvoice actions; all English labels
  - PurchaseOrderResource: removed isLocked method + import
  - EditPurchaseOrder: removed isLocked checks
  - ListPurchaseOrders: removed isLocked checks

- [x] **Task 5: Clean Product and Company Models**
  - Product model: removed supplier_id, min_order_qty from fillable/casts; removed supplier() relationship
  - Company model: removed settings from fillable/casts
  - ProductResource: removed isLocked, isPpnCompany, isReadOnly methods + Company import
  - ProductForm: removed supplier_id field, min_order_qty field; English labels
  - ProductsTable: removed supplier.name, min_order_qty columns; removed isReadOnly visibility checks; English labels
  - CreateProduct: removed isReadOnly redirect
  - EditProduct: removed isReadOnly checks
  - ListProducts: removed isReadOnly checks

- [x] **Task 6: Rewrite Invoice Templates**
  - sales-invoice-view.blade.php: rewritten with generic corporate design (dynamic company header, customer details, modern table, signature section, English labels)
  - purchase-invoice-view.blade.php: rewritten with same corporate design (supplier details instead of customer)
  - sales-order-view.blade.php: updated to English labels (removed order_type display, item_name references, Indonesian text)

- [x] **Task 7: Switch Labels to English**
  - All Filament resource tables: Indonesian labels → English (Pemasok→Supplier, Tanggal→Date, Dibuat Oleh→Created By, etc.)
  - All Filament form schemas: Indonesian labels → English
  - All Filament page actions: Indonesian modal text → English
  - All filter labels and indicators: Indonesian → English
  - All export/import helpers: Indonesian text → English

- [x] **Task 8: Final Cleanup**
  - Deleted: SalesOrderController.php (Pelangi integration API)
  - Deleted: UpdateSalesOrderItemsProduction.php command
  - Removed sales-orders API routes from routes/api.php
  - Cleaned PurchaseOrderController: removed order_type, department_id, advance_payment_id, sales_order_id, job_id, related_order_id, supplier_id/min_order_qty on products
  - Cleaned MasterDataController: removed supplier_id, min_order_qty from product sync
  - Cleaned SalesInvoiceForm: removed jb_job_number_display, client_po_number_display, order_type filter, item_name field
  - Cleaned SalesInvoicesTable: removed salesOrder.client_po_number, salesOrder.jb_job_number columns
  - Cleaned PurchaseInvoicesTable: removed purchaseOrder.salesOrder columns
  - Cleaned PurchaseInvoiceForm: removed item_name from PO auto-fill and form field
  - Cleaned GoodsReceiptForm: removed item_name from PO auto-fill and form field
  - Cleaned PurchaseReturnForm: removed item_name from receipt auto-fill and form field
  - Cleaned model fillables: removed item_name from SalesInvoiceItem, PurchaseInvoiceItem, GoodsReceiptItem, PurchaseReturnItem
  - Cleaned imports: removed order_type, item_name, department_id, min_order_qty, supplier_id references
  - Cleaned exports: removed order_type, department_id, min_order_qty columns; English headers
  - Cleaned ImportProductsAction: updated helperText to match new template columns
