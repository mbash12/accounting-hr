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

## In Progress

- [ ] **Task 4: Clean PurchaseOrder Model and Resources**
  - PurchaseOrder model read — NEEDS: remove order_type, sales_order_id, related_order_id, advance_payment_id, department_id, job_id from fillable/casts; remove salesOrder(), job(), department(), relatedOrder(), advancePayment() relationships; update notification text to English
  - PurchaseOrderItem model read — NEEDS: remove item_name from fillable
  - PurchaseOrderForm read — NEEDS: remove sales_order_id dropdown + auto-fill logic, department_id field, item_name field, order_type hidden field; English labels
  - PurchaseOrdersTable read — NEEDS: remove salesOrder.order_number, salesOrder.job_number, salesOrder.client_po_number, salesOrder.jb_job_number, department.name columns; English labels
  - Also need to read and clean: PurchaseOrderResource.php, EditPurchaseOrder.php, ListPurchaseOrders.php
  - Note: createGoodsReceipt and createPurchaseInvoice actions reference `$purchaseOrder->job_id` — remove that reference

## Pending

- [ ] **Task 5: Clean Product and Company Models**
  - Product model: remove supplier_id, min_order_qty from fillable; remove supplier() relationship
  - Company model: remove settings from fillable/casts
  - ProductResource: remove isLocked, isReadOnly, isPpnCompany
  - ProductForm: remove supplier_id, min_order_qty fields
  - ProductsTable: remove supplier.name, min_order_qty columns
  - Product pages: remove isReadOnly/isLocked conditional logic

- [ ] **Task 6: Rewrite Invoice Templates**
  - Rewrite `resources/views/filament/pages/sales-invoice-view.blade.php` — match generic pattern from sales-order-view.blade.php
  - Rewrite `resources/views/filament/pages/purchase-invoice-view.blade.php`
  - Remove: hardcoded BCA bank details, Pelangi address, legal notes, Pelangi signature
  - Use dynamic: $company->photo, $company->name, $company->billing_address_*, $company->tax_id

- [ ] **Task 7: Switch Labels to English**
  - All remaining Filament resources (SO/PO already done in Tasks 3-4)
  - SalesInvoiceResource, PurchaseInvoiceResource, DeliveryDocumentResource, GoodsReceiptResource, SalesReturnResource, PurchaseReturnResource, ContactResource, ProductResource
  - Model boot() notifications
  - Print template title text

- [ ] **Task 8: Final Cleanup**
  - grep for orphaned references to removed columns/methods
  - Remove unused imports
  - Verify migration status
