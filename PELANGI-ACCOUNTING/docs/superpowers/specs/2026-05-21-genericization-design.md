# Genericization Design Spec

**Date:** 2026-05-21
**Scope:** Master Data, Sales Orders, Purchase Orders, Invoices
**Goal:** Remove Pelangi-specific business logic and make the app a generic Indonesian accounting system

---

## Context

This app was originally built for PT. Pelangi Sentral Kreasi. It is no longer company-specific and needs to be genericized for broader Indonesian use. Indonesian tax compliance features (PPN, PKP, PTKP, BPJS, NPWP) remain — only Pelangi business logic is removed.

## Section 1: Remove Pelangi Business Logic

### Columns to Remove (via migration)

**SalesOrder:**
- `order_type` (deposit/standar/aktual)
- `related_order_id` (self-referential FK for deposit-aktual linking)
- `job_number`
- `jb_job_number`
- `client_po_number`
- `advance_payment_id`
- `job_id` (FK to Project)

**SalesOrderItem:**
- `item_name`
- `is_production`

**PurchaseOrder:**
- `order_type`
- `related_order_id`
- `advance_payment_id`
- `department_id`
- `job_id`

**PurchaseOrderItem:**
- `item_name`

**Product:**
- `supplier_id`
- `min_order_qty`

**Company:**
- `include_ppn` (orphaned, never wired into model)
- `settings` (JSON column, never used)

**SalesInvoice:**
- `synced_to_inventory_at`
- `sync_status`
- `sync_error`
- `sync_retry_count`
- `last_sync_attempt_at`

### Code to Remove

**Files to delete:**
- `app/Services/InventorySyncService.php`
- `app/Jobs/SyncInvoiceToInventoryJob.php`
- `app/Models/InvoiceSyncJob.php`
- `app/Observers/SalesInvoiceObserver.php`
- `app/Console/Commands/SyncInvoicesToInventory.php`
- `app/Http/Controllers/Api/InvoiceSyncController.php`
- `app/Filament/Pages/SyncMonitoring.php`
- `resources/views/filament/pages/sync-monitoring.blade.php`

**Files to modify:**
- `app/Providers/AppServiceProvider.php` — remove `SalesInvoice::observe()` registration
- `app/Providers/Filament/MainPanelProvider.php` — remove `SyncMonitoring::class` from pages
- `routes/api.php` — remove invoice-sync routes
- `routes/web.php` — remove invoice-sync routes

**SalesOrder model:**
- Remove all `order_type` logic (deposit/aktual/standar)
- Remove `related_order_id`, `relatedDepositOrders()`, `relatedAktualOrders()`, `getOrderGroupSummary()`, `allRelatedOrders()`, `referencingOrders()`, `ordersByJobNumber()`, `hasRelatedOrders()`
- Remove `advance_payment_id` relationship
- Remove `job_id`, `job_number`, `jb_job_number`, `client_po_number` from fillable and relationships
- Remove `ViewSalesOrderDetail` page entirely (it's built around order groups)

**SalesOrderItem model:**
- Remove `item_name`, `is_production` from fillable

**PurchaseOrder model:**
- Remove `order_type` logic
- Remove `related_order_id` and related methods
- Remove `advance_payment_id` relationship
- Remove `department_id` relationship
- Remove `sales_order_id` relationship (SO-to-PO linking)
- Remove `job_id`

**PurchaseOrderItem model:**
- Remove `item_name` from fillable

**Product model:**
- Remove `supplier_id` relationship
- Remove `min_order_qty` from fillable

**ProductResource:**
- Remove `isLocked()` and `isReadOnly()` methods
- Remove all conditional lock/disable logic from Create/Edit/List pages

**SalesOrderResource:**
- Remove `isLocked()` method
- Remove all conditional lock/disable logic

**SalesOrderForm:**
- Remove order_type selector
- Remove related_order_id dropdown
- Remove job_number, jb_job_number, client_po_number fields
- Remove is_production toggle on items
- Remove item_name field on items
- Remove "Select Deposit" dropdown logic

**PurchaseOrderForm:**
- Remove sales_order_id dropdown and auto-fill logic
- Remove department_id field
- Remove item_name field on items

**SalesOrdersTable:**
- Remove createPurchaseOrder action (SO-to-PO generation)
- Remove order_type column/filter
- Remove jb_job_number, client_po_number columns
- Remove ViewSalesOrderDetail page reference

**PurchaseOrdersTable:**
- Remove salesOrder.order_number column
- Remove salesOrder.job_number, client_po_number, jb_job_number columns
- Remove department.name column

**SalesInvoice model:**
- Remove sync-related fields from fillable and casts

**Filament Resources:**
- Remove `ViewSalesOrderDetail` page and its Blade view (`sales-order-detail-view.blade.php`)

## Section 2: Rewrite Invoice Templates

### Templates to Rewrite

**`resources/views/filament/pages/sales-invoice-view.blade.php`**
- Replace Pelangi-specific content with generic pattern from `sales-order-view.blade.php`
- Company header: logo (`$company->photo`), name, billing address, NPWP — all dynamic
- Title: "Sales Invoice" (English)
- Customer info: name, address — dynamic
- Meta: Invoice No, Date, Reference, Payment Term, Due Date
- Items table: Description, QTY, Unit Price, Total
- Totals: Subtotal, Discount, Tax, Total
- Remove: hardcoded BCA bank details, legal notes, Pelangi signature
- Signature: use `$company->name` dynamically
- Same CSS structure and `?print=1` mechanism as other generic templates

**`resources/views/filament/pages/purchase-invoice-view.blade.php`**
- Same rewrite as sales invoice, adapted for purchase context
- Title: "Purchase Invoice"
- Supplier info instead of customer

### Out of Scope (templates)
- Payment vouchers, journal vouchers — different template style, less Pelangi-specific, follow-up task

## Section 3: English Labels

### Filament Resources (Navigation)

| Resource | Current Label | New Label |
|----------|--------------|-----------|
| SalesOrderResource | Pesanan Penjualan | Sales Orders |
| PurchaseOrderResource | Pesanan Pembelian | Purchase Orders |
| SalesInvoiceResource | Faktur Penjualan | Sales Invoices |
| PurchaseInvoiceResource | Faktur Pembelian | Purchase Invoices |
| DeliveryDocumentResource | Surat Jalan | Delivery Documents |
| GoodsReceiptResource | Penerimaan Barang | Goods Receipts |
| SalesReturnResource | Retur Penjualan | Sales Returns |
| PurchaseReturnResource | Retur Pembelian | Purchase Returns |
| ContactResource | Kontak | Contacts |
| ProductResource | Produk | Products |

### Navigation Groups

| Current | New |
|---------|-----|
| Penjualan | Sales |
| Pembelian | Purchasing |

### Model Notifications

All Indonesian notification text in `boot()` methods → English. Example:
- "Pesanan memiliki dokumen pengiriman atau invoice yang terkunci." → "This order has locked delivery documents or invoices."

### Form Labels

All form field labels, placeholders, helper text → English. Example:
- "Jumlah Minimum Pesanan" → "Minimum Order Qty"
- "Barang / Jasa" → "Goods / Services"
- "No. Pesanan" → "Order No."
- "Tanggal" → "Date"

### Table Column Headers

All table column labels → English.

## Section 4: Implementation Order

1. **Remove sync monitoring** — delete files, observer, routes, page registration, migration to drop sync columns
2. **Remove Pelangi business logic** — migrations to drop columns, remove code references, remove ViewSalesOrderDetail
3. **Rewrite invoice templates** — sales-invoice-view and purchase-invoice-view
4. **Switch labels to English** — all Filament resources, models, notifications
5. **Clean up** — remove unused imports, dead code, orphaned references

Each step is independently committable and testable.

## What Stays (Not Touched)

- PPN, PKP, NPWP, PTKP, BPJS — Indonesian tax compliance
- Multi-tenancy (`company_id`, `HasCompanyFilter`)
- `Journalable` trait, `HasAutoGeneratedCode` trait
- `is_closed` on orders — generic concept
- `discount_percentage`, `other_charges`, GL account overrides
- `delivery_meta`/`receipt_meta`/`invoice_meta` JSON tracking
- `delivered_quantity`/`received_quantity`/`invoiced_quantity` on items
- `tax_id` on Product
- HR & Payroll modules (entirely)
- Financial reports
- Cash & Bank modules
- All payment/receipt modules
