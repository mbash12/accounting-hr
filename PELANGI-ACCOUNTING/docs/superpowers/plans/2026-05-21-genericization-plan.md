# Genericization Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove Pelangi-specific business logic and make the app a generic Indonesian accounting system

**Architecture:** Incremental removal of company-specific code across 5 phases: sync module removal, business logic cleanup, invoice template rewrite, English labels, and final cleanup. DB columns are dropped via migrations; code references are removed from models, forms, tables, and resources.

**Tech Stack:** Laravel 12, Filament v4, PostgreSQL, Blade templates

---

## File Structure

### Files to Delete
- `app/Services/InventorySyncService.php`
- `app/Jobs/SyncInvoiceToInventoryJob.php`
- `app/Models/InvoiceSyncJob.php`
- `app/Observers/SalesInvoiceObserver.php`
- `app/Console/Commands/SyncInvoicesToInventory.php`
- `app/Http/Controllers/Api/InvoiceSyncController.php`
- `app/Filament/Pages/SyncMonitoring.php`
- `resources/views/filament/pages/sync-monitoring.blade.php`
- `resources/views/filament/pages/sales-order-detail-view.blade.php`

### Files to Modify (major)
- `app/Models/SalesOrder.php` — remove order_type, related_order_id, job fields, advance_payment_id
- `app/Models/SalesOrderItem.php` — remove item_name, is_production
- `app/Models/PurchaseOrder.php` — remove order_type, related_order_id, advance_payment_id, department_id, sales_order_id, job_id
- `app/Models/PurchaseOrderItem.php` — remove item_name
- `app/Models/Product.php` — remove supplier_id, min_order_qty
- `app/Models/Company.php` — remove include_ppn, settings
- `app/Models/SalesInvoice.php` — remove sync fields
- `app/Filament/Resources/SalesOrders/SalesOrderResource.php` — remove isLocked, detail page
- `app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php` — remove order_type, job fields, item_name, is_production
- `app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php` — remove order_type, job columns, createPO action
- `app/Filament/Resources/SalesOrders/Pages/ViewSalesOrderDetail.php` — delete
- `app/Filament/Resources/PurchaseOrders/Schemas/PurchaseOrderForm.php` — remove sales_order_id, department_id, item_name
- `app/Filament/Resources/PurchaseOrders/Tables/PurchaseOrdersTable.php` — remove SO columns, department
- `app/Filament/Resources/Products/ProductResource.php` — remove isLocked, isReadOnly
- `app/Filament/Resources/Products/Schemas/ProductForm.php` — remove supplier_id, min_order_qty
- `app/Filament/Resources/Products/Tables/ProductsTable.php` — remove supplier, min_order_qty columns
- `app/Providers/AppServiceProvider.php` — remove SalesInvoiceObserver registration
- `app/Providers/Filament/MainPanelProvider.php` — remove SyncMonitoring page
- `routes/api.php` — remove invoice-sync routes
- `routes/web.php` — remove invoice-sync routes

### Files to Rewrite
- `resources/views/filament/pages/sales-invoice-view.blade.php`
- `resources/views/filament/pages/purchase-invoice-view.blade.php`

### New Files
- `database/migrations/2026_05_21_000001_drop_pelangi_columns.php` — drop custom columns
- `database/migrations/2026_05_21_000002_drop_sync_tables.php` — drop invoice_sync_jobs table

---

### Task 1: Remove Sync Monitoring Module

**Files:**
- Delete: `app/Services/InventorySyncService.php`
- Delete: `app/Jobs/SyncInvoiceToInventoryJob.php`
- Delete: `app/Models/InvoiceSyncJob.php`
- Delete: `app/Observers/SalesInvoiceObserver.php`
- Delete: `app/Console/Commands/SyncInvoicesToInventory.php`
- Delete: `app/Http/Controllers/Api/InvoiceSyncController.php`
- Delete: `app/Filament/Pages/SyncMonitoring.php`
- Delete: `resources/views/filament/pages/sync-monitoring.blade.php`
- Modify: `app/Providers/AppServiceProvider.php` — remove observer registration
- Modify: `app/Providers/Filament/MainPanelProvider.php` — remove SyncMonitoring page
- Modify: `routes/api.php` — remove invoice-sync routes
- Modify: `routes/web.php` — remove invoice-sync routes
- Create: `database/migrations/2026_05_21_000002_drop_sync_tables.php`

- [ ] **Step 1: Delete sync service files**

```bash
rm app/Services/InventorySyncService.php
rm app/Jobs/SyncInvoiceToInventoryJob.php
rm app/Models/InvoiceSyncJob.php
rm app/Observers/SalesInvoiceObserver.php
rm app/Console/Commands/SyncInvoicesToInventory.php
rm app/Http/Controllers/Api/InvoiceSyncController.php
rm app/Filament/Pages/SyncMonitoring.php
rm resources/views/filament/pages/sync-monitoring.blade.php
```

- [ ] **Step 2: Remove observer registration from AppServiceProvider**

Read `app/Providers/AppServiceProvider.php`, find and remove the line:
```php
SalesInvoice::observe(SalesInvoiceObserver::class);
```
Also remove the `use` statement for `SalesInvoiceObserver` if present.

- [ ] **Step 3: Remove SyncMonitoring from MainPanelProvider**

Read `app/Providers/Filament/MainPanelProvider.php`, find the `pages` array and remove `SyncMonitoring::class`. Remove the `use` statement if present.

- [ ] **Step 4: Remove sync routes**

Read `routes/api.php` and remove the invoice-sync route group.
Read `routes/web.php` and remove the invoice-sync route group.

- [ ] **Step 5: Create migration to drop sync tables and columns**

Create `database/migrations/2026_05_21_000002_drop_sync_tables.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('invoice_sync_jobs');

        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'synced_to_inventory_at',
                'sync_status',
                'sync_error',
                'sync_retry_count',
                'last_sync_attempt_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->timestamp('synced_to_inventory_at')->nullable();
            $table->string('sync_status')->nullable()->default('pending');
            $table->text('sync_error')->nullable();
            $table->integer('sync_retry_count')->default(0);
            $table->timestamp('last_sync_attempt_at')->nullable();
        });
    }
};
```

- [ ] **Step 6: Clean SalesInvoice model**

Read `app/Models/SalesInvoice.php`. Remove sync-related fields from `$fillable` and `casts()`:
- `synced_to_inventory_at`, `sync_status`, `sync_error`, `sync_retry_count`, `last_sync_attempt_at`

- [ ] **Step 7: Run migration**

```bash
php artisan migrate
```

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "remove: sync monitoring module and Pelangi inventory integration"
```

---

### Task 2: Drop Pelangi Business Columns

**Files:**
- Create: `database/migrations/2026_05_21_000001_drop_pelangi_columns.php`

- [ ] **Step 1: Create migration**

Create `database/migrations/2026_05_21_000001_drop_pelangi_columns.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SalesOrder
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'related_order_id',
                'job_number',
                'jb_job_number',
                'client_po_number',
                'advance_payment_id',
                'job_id',
            ]);
        });

        // SalesOrderItem
        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'is_production']);
        });

        // PurchaseOrder
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'related_order_id',
                'advance_payment_id',
                'department_id',
                'sales_order_id',
                'job_id',
            ]);
        });

        // PurchaseOrderItem
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_name']);
        });

        // Product
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['supplier_id', 'min_order_qty']);
        });

        // Company
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['include_ppn', 'settings']);
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('order_type')->default('standar');
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->string('job_number')->nullable();
            $table->string('jb_job_number')->nullable();
            $table->string('client_po_number')->nullable();
            $table->unsignedBigInteger('advance_payment_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->string('item_name')->nullable();
            $table->boolean('is_production')->default(false);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('order_type')->default('standar');
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->unsignedBigInteger('advance_payment_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->string('item_name')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('min_order_qty', 15, 2)->nullable();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('include_ppn')->default(false);
            $table->json('settings')->nullable();
        });
    }
};
```

- [ ] **Step 2: Run migration**

```bash
php artisan migrate
```

- [ ] **Step 3: Commit**

```bash
git add database/migrations/2026_05_21_000001_drop_pelangi_columns.php
git commit -m "migration: drop Pelangi-specific columns from orders, products, companies"
```

---

### Task 3: Clean SalesOrder Model and Resources

**Files:**
- Modify: `app/Models/SalesOrder.php`
- Modify: `app/Models/SalesOrderItem.php`
- Delete: `app/Filament/Resources/SalesOrders/Pages/ViewSalesOrderDetail.php`
- Delete: `resources/views/filament/pages/sales-order-detail-view.blade.php`
- Modify: `app/Filament/Resources/SalesOrders/SalesOrderResource.php`
- Modify: `app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php`
- Modify: `app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php`
- Modify: `app/Filament/Resources/SalesOrders/Pages/EditSalesOrder.php`
- Modify: `app/Filament/Resources/SalesOrders/Pages/ListSalesOrders.php`

- [ ] **Step 1: Clean SalesOrder model**

Read `app/Models/SalesOrder.php`. Remove from `$fillable`:
- `order_type`, `related_order_id`, `job_number`, `jb_job_number`, `client_po_number`, `advance_payment_id`, `job_id`

Remove methods:
- `relatedDepositOrders()`, `relatedAktualOrders()`, `getOrderGroupSummary()`, `allRelatedOrders()`, `referencingOrders()`, `ordersByJobNumber()`, `hasRelatedOrders()`

Remove relationships:
- `advancePayment()`, `job()`, `relatedOrder()`

Remove order_type references from `boot()` deletion protection if any.

Remove `$casts` entries for removed columns.

- [ ] **Step 2: Clean SalesOrderItem model**

Read `app/Models/SalesOrderItem.php`. Remove from `$fillable`:
- `item_name`, `is_production`

Remove `$casts` entries if present.

- [ ] **Step 3: Delete ViewSalesOrderDetail**

```bash
rm app/Filament/Resources/SalesOrders/Pages/ViewSalesOrderDetail.php
rm resources/views/filament/pages/sales-order-detail-view.blade.php
```

- [ ] **Step 4: Clean SalesOrderResource**

Read `app/Filament/Resources/SalesOrders/SalesOrderResource.php`. Remove:
- `isLocked()` method
- Reference to `ViewSalesOrderDetail` in `getPages()`
- Any conditional lock/disable logic

- [ ] **Step 5: Clean SalesOrderForm**

Read `app/Filament/Resources/SalesOrders/Schemas/SalesOrderForm.php`. Remove:
- `order_type` select field and all deposit/aktual logic
- `related_order_id` dropdown
- `job_number`, `jb_job_number`, `client_po_number` fields
- `is_production` toggle on items
- `item_name` field on items
- Any inline project/job creation

- [ ] **Step 6: Clean SalesOrdersTable**

Read `app/Filament/Resources/SalesOrders/Tables/SalesOrdersTable.php`. Remove:
- `createPurchaseOrder` table action
- `order_type` column and filter
- `jb_job_number`, `client_po_number` columns
- Reference to ViewSalesOrderDetail page

- [ ] **Step 7: Clean EditSalesOrder page**

Read `app/Filament/Resources/SalesOrders/Pages/EditSalesOrder.php`. Remove `isLocked()` references from form actions and form disable logic.

- [ ] **Step 8: Clean ListSalesOrders page**

Read `app/Filament/Resources/SalesOrders/Pages/ListSalesOrders.php`. Remove `isLocked()` references from header actions.

- [ ] **Step 9: Commit**

```bash
git add -A
git commit -m "refactor: remove Pelangi business logic from SalesOrder module"
```

---

### Task 4: Clean PurchaseOrder Model and Resources

**Files:**
- Modify: `app/Models/PurchaseOrder.php`
- Modify: `app/Models/PurchaseOrderItem.php`
- Modify: `app/Filament/Resources/PurchaseOrders/Schemas/PurchaseOrderForm.php`
- Modify: `app/Filament/Resources/PurchaseOrders/Tables/PurchaseOrdersTable.php`

- [ ] **Step 1: Clean PurchaseOrder model**

Read `app/Models/PurchaseOrder.php`. Remove from `$fillable`:
- `order_type`, `related_order_id`, `advance_payment_id`, `department_id`, `sales_order_id`, `job_id`

Remove methods:
- `allRelatedOrders()`, `referencingOrders()`, `hasRelatedOrders()`, `getOrderGroupSummary()` or similar

Remove relationships:
- `advancePayment()`, `department()`, `salesOrder()`, `job()`, `relatedOrder()`

Remove `$casts` entries for removed columns.

- [ ] **Step 2: Clean PurchaseOrderItem model**

Read `app/Models/PurchaseOrderItem.php`. Remove from `$fillable`:
- `item_name`

- [ ] **Step 3: Clean PurchaseOrderForm**

Read `app/Filament/Resources/PurchaseOrders/Schemas/PurchaseOrderForm.php`. Remove:
- `sales_order_id` dropdown and auto-fill-from-SO logic
- `department_id` field
- `item_name` field on items
- `order_type` hidden field if present

- [ ] **Step 4: Clean PurchaseOrdersTable**

Read `app/Filament/Resources/PurchaseOrders/Tables/PurchaseOrdersTable.php`. Remove:
- `salesOrder.order_number` column
- `salesOrder.job_number`, `salesOrder.client_po_number`, `salesOrder.jb_job_number` columns
- `department.name` column

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "refactor: remove Pelangi business logic from PurchaseOrder module"
```

---

### Task 5: Clean Product and Company Models

**Files:**
- Modify: `app/Models/Product.php`
- Modify: `app/Models/Company.php`
- Modify: `app/Filament/Resources/Products/ProductResource.php`
- Modify: `app/Filament/Resources/Products/Schemas/ProductForm.php`
- Modify: `app/Filament/Resources/Products/Tables/ProductsTable.php`
- Modify: `app/Filament/Resources/Products/Pages/CreateProduct.php`
- Modify: `app/Filament/Resources/Products/Pages/EditProduct.php`
- Modify: `app/Filament/Resources/Products/Pages/ListProducts.php`

- [ ] **Step 1: Clean Product model**

Read `app/Models/Product.php`. Remove from `$fillable`:
- `supplier_id`, `min_order_qty`

Remove relationship:
- `supplier()`

Remove `$casts` entries if present.

- [ ] **Step 2: Clean Company model**

Read `app/Models/Company.php`. Remove from `$fillable`:
- `settings`

Remove `$casts` entries for `settings`. (Note: `include_ppn` was never in fillable, just in DB — migration handles drop.)

- [ ] **Step 3: Clean ProductResource**

Read `app/Filament/Resources/Products/ProductResource.php`. Remove:
- `isLocked()` method
- `isReadOnly()` method
- `isPpnCompany()` method (if used only for lock logic)
- All conditional lock/disable logic

- [ ] **Step 4: Clean ProductForm**

Read `app/Filament/Resources/Products/Schemas/ProductForm.php`. Remove:
- `supplier_id` field
- `min_order_qty` field

- [ ] **Step 5: Clean ProductsTable**

Read `app/Filament/Resources/Products/Tables/ProductsTable.php`. Remove:
- `supplier.name` column
- `min_order_qty` column

- [ ] **Step 6: Clean Product pages**

Read CreateProduct, EditProduct, ListProducts pages. Remove `isReadOnly()` and `isLocked()` conditional logic.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "refactor: remove Pelangi-specific fields from Product and Company"
```

---

### Task 6: Rewrite Invoice Templates

**Files:**
- Rewrite: `resources/views/filament/pages/sales-invoice-view.blade.php`
- Rewrite: `resources/views/filament/pages/purchase-invoice-view.blade.php`

- [ ] **Step 1: Read the generic template pattern**

Read `resources/views/filament/pages/sales-order-view.blade.php` to understand the generic template structure (CSS, layout, company header, `?print=1` mechanism).

- [ ] **Step 2: Rewrite sales-invoice-view.blade.php**

Rewrite to match the generic pattern. Structure:
- Company header: logo (`$company->photo`), name, billing address, NPWP — all dynamic from `$record->company`
- Title: "Sales Invoice"
- Customer: name, billing address
- Meta: Invoice No, Date, Reference, Payment Term, Due Date
- Items table: Description, QTY, Unit Price, Total
- Totals: Subtotal, Discount, Tax (PPN), Total
- Signature: `$record->company->name`
- Same CSS as generic templates
- Same `?print=1` mechanism
- No hardcoded Pelangi content

- [ ] **Step 3: Rewrite purchase-invoice-view.blade.php**

Same structure as sales invoice, adapted for purchase context:
- Title: "Purchase Invoice"
- Supplier info instead of customer

- [ ] **Step 4: Commit**

```bash
git add resources/views/filament/pages/sales-invoice-view.blade.php
git add resources/views/filament/pages/purchase-invoice-view.blade.php
git commit -m "rewrite: generic invoice templates matching standard document pattern"
```

---

### Task 7: Switch Labels to English

**Files:**
- Modify: All Filament resource files for Sales, Purchase, Master Data modules
- Modify: Model `boot()` notification text
- Modify: Form labels, table column headers

- [ ] **Step 1: Update SalesOrderResource labels**

Read `app/Filament/Resources/SalesOrders/SalesOrderResource.php`. Change:
- `getNavigationLabel()` → "Sales Orders"
- Navigation group → "Sales"
- Model label → "Sales Order"
- Plural label → "Sales Orders"

- [ ] **Step 2: Update SalesOrder model notifications**

Read `app/Models/SalesOrder.php`. Change any Indonesian notification text in `boot()` to English.

- [ ] **Step 3: Update SalesOrderForm labels**

Read the form schema. Change all Indonesian labels to English:
- "Pesanan Penjualan" → "Sales Order"
- "Pelanggan" → "Customer"
- "No. Pesanan" → "Order No."
- "Tanggal" → "Date"
- "Tipe Pesanan" → "Order Type" (if any remain)
- Item field labels

- [ ] **Step 4: Update SalesOrdersTable column headers**

Read the table config. Change all Indonesian column labels to English.

- [ ] **Step 5: Update PurchaseOrderResource labels**

Same pattern as SalesOrder. Change navigation label to "Purchase Orders", group to "Purchasing".

- [ ] **Step 6: Update PurchaseOrder model and form labels**

Change all Indonesian text to English.

- [ ] **Step 7: Update PurchaseOrdersTable column headers**

Change all Indonesian column labels to English.

- [ ] **Step 8: Update SalesInvoiceResource labels**

Change navigation label to "Sales Invoices".

- [ ] **Step 9: Update PurchaseInvoiceResource labels**

Change navigation label to "Purchase Invoices".

- [ ] **Step 10: Update DeliveryDocumentResource labels**

Change navigation label to "Delivery Documents". Change "Surat Jalan" references.

- [ ] **Step 11: Update GoodsReceiptResource labels**

Change navigation label to "Goods Receipts". Change "Penerimaan Barang" references.

- [ ] **Step 12: Update SalesReturnResource labels**

Change navigation label to "Sales Returns".

- [ ] **Step 13: Update PurchaseReturnResource labels**

Change navigation label to "Purchase Returns".

- [ ] **Step 14: Update ContactResource labels**

Change "Kontak" to "Contacts", "Pelanggan" to "Customer", "Pemasok" to "Supplier".

- [ ] **Step 15: Update ProductResource labels**

Change "Produk" to "Products", "Barang/Jasa" to "Goods/Services".

- [ ] **Step 16: Update remaining print template labels**

Update Indonesian text in the other print templates (sales-order-view, purchase-order-view, delivery-document-view, goods-receipt-view, sales-return-view, purchase-return-view) — title text only, not structural changes.

- [ ] **Step 17: Commit**

```bash
git add -A
git commit -m "refactor: switch all UI labels from Indonesian to English"
```

---

### Task 8: Final Cleanup

- [ ] **Step 1: Search for orphaned references**

Search for any remaining references to removed columns/methods:
```bash
grep -r "order_type" app/ --include="*.php" -l
grep -r "item_name" app/ --include="*.php" -l
grep -r "is_production" app/ --include="*.php" -l
grep -r "job_number" app/ --include="*.php" -l
grep -r "jb_job_number" app/ --include="*.php" -l
grep -r "client_po_number" app/ --include="*.php" -l
grep -r "related_order_id" app/ --include="*.php" -l
grep -r "advance_payment_id" app/ --include="*.php" -l
grep -r "supplier_id" app/ --include="*.php" -l
grep -r "min_order_qty" app/ --include="*.php" -l
grep -r "isLocked" app/ --include="*.php" -l
grep -r "isReadOnly" app/ --include="*.php" -l
grep -r "SyncMonitoring" app/ --include="*.php" -l
grep -r "InventorySync" app/ --include="*.php" -l
```

Fix any remaining references found.

- [ ] **Step 2: Check for unused imports**

Search for `use` statements referencing deleted classes and remove them.

- [ ] **Step 3: Run migrations to verify**

```bash
php artisan migrate:status
```

- [ ] **Step 4: Final commit**

```bash
git add -A
git commit -m "chore: final cleanup of orphaned references and unused imports"
```
