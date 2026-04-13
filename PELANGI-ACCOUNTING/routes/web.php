<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\Api\InvoiceSyncController;
use App\Services\InventorySyncService;

use App\Http\Controllers\OpeningBalanceUpdateController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/journal-voucher/{id}/print', [JournalVoucherController::class, 'print'])->name('journal-voucher.print');
    Route::get('/journal-voucher/{id}/print-voucher', [JournalVoucherController::class, 'printVoucher'])->name('journal-voucher.print-voucher');
    Route::get('/journal-voucher/{id}/pdf', [JournalVoucherController::class, 'pdf'])->name('journal-voucher.pdf');

    Route::get('/cash-receipt/{id}/print-voucher', [App\Http\Controllers\CashReceiptController::class, 'printVoucher'])->name('cash-receipt.print-voucher');
    Route::get('/cash-disbursement/{id}/print-voucher', [App\Http\Controllers\CashDisbursementController::class, 'printVoucher'])->name('cash-disbursement.print-voucher');

    Route::get('/receivable-payment/{id}/print', [App\Http\Controllers\PaymentPrintController::class, 'printReceivable'])->name('receivable-payment.print');
    Route::get('/payable-payment/{id}/print', [App\Http\Controllers\PaymentPrintController::class, 'printPayable'])->name('payable-payment.print');

    // Route to update existing opening balance dates
    Route::post('/internal/opening-balances/update-dates', [OpeningBalanceUpdateController::class, 'updateDates'])->name('opening-balances.update-dates');

    // Invoice Sync Monitoring Routes (internal use - no API token required)
    Route::prefix('internal/invoice-sync')->group(function () {
        Route::get('/', [InvoiceSyncController::class, 'index']);
        Route::get('/stats', [InvoiceSyncController::class, 'stats']);
        Route::post('/bulk-retry', [InvoiceSyncController::class, 'bulkRetry']);
        Route::get('/{syncJobId}/status', [InvoiceSyncController::class, 'status']);
        Route::post('/{syncJobId}/retry', [InvoiceSyncController::class, 'retrySync']);
        Route::post('/{syncJobId}/queue-retry', [InvoiceSyncController::class, 'queueRetry']);
        Route::delete('/clear-data', [InvoiceSyncController::class, 'clearData']);
    });
});
