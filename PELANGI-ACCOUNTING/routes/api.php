<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Api;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\SalesOrderController;
use App\Http\Controllers\Api\InvoiceSyncController;

Route::prefix('master')->middleware([Api::class])->group(function () {
    Route::get('/coa', [MasterDataController::class, 'coa']);
    Route::get('/vendor', [MasterDataController::class, 'vendor']);
    Route::get('/products', [MasterDataController::class, 'itemmaster']);
    Route::get('/categories', [MasterDataController::class, 'categories']);
    Route::get('/department', [MasterDataController::class, 'department']);
    Route::get('/unit', [MasterDataController::class, 'unit']);
    Route::get('/taxes', [MasterDataController::class, 'taxes']);
    Route::get('/customers', [MasterDataController::class, 'customers']);
    Route::post('/products', [MasterDataController::class, 'syncProducts']);
});

Route::prefix('purchase')->middleware([Api::class])->group(function () {
    Route::post('/check', [PurchaseOrderController::class, 'detailPurchaseOrder']);
    Route::post('/store', [PurchaseOrderController::class, 'storePurchaseOrder']);
});

Route::prefix('sales-orders')->middleware([Api::class])->group(function () {
    Route::get('/list', [SalesOrderController::class, 'list']);
    Route::get('/detail', [SalesOrderController::class, 'detail']);
    Route::post('/sync', [SalesOrderController::class, 'sync']);
});

// Invoice Sync Monitoring Routes (external API with token)
Route::prefix('invoice-sync')->middleware([Api::class])->group(function () {
    Route::get('/', [InvoiceSyncController::class, 'index']);
    Route::get('/stats', [InvoiceSyncController::class, 'stats']);
    Route::post('/bulk-retry', [InvoiceSyncController::class, 'bulkRetry']);
    Route::get('/{syncJobId}/status', [InvoiceSyncController::class, 'status']);
    Route::post('/{syncJobId}/retry', [InvoiceSyncController::class, 'retrySync']);
    Route::post('/{syncJobId}/queue-retry', [InvoiceSyncController::class, 'queueRetry']);
});
