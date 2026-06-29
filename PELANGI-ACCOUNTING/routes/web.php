<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\PayslipPdfController;
use App\Http\Controllers\NuxiAppController;

use App\Http\Controllers\OpeningBalanceUpdateController;

Route::get('/', function () {
    return view('welcome');
});

// Built Nuxt (NUXI) SPA — served from public/user/. Static assets in that
// directory are served directly by the web server; this catch-all only
// handles deep links the web server could not resolve (SPA history routes).
Route::get('/user/{any?}', [NuxiAppController::class, 'serve'])
    ->where('any', '.*')
    ->name('nuxi.app');

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/journal-voucher/{id}/print', [JournalVoucherController::class, 'print'])->name('journal-voucher.print');
    Route::get('/journal-voucher/{id}/print-voucher', [JournalVoucherController::class, 'printVoucher'])->name('journal-voucher.print-voucher');
    Route::get('/journal-voucher/{id}/pdf', [JournalVoucherController::class, 'pdf'])->name('journal-voucher.pdf');

    Route::get('/cash-receipt/{id}/print-voucher', [App\Http\Controllers\CashReceiptController::class, 'printVoucher'])->name('cash-receipt.print-voucher');
    Route::get('/cash-disbursement/{id}/print-voucher', [App\Http\Controllers\CashDisbursementController::class, 'printVoucher'])->name('cash-disbursement.print-voucher');

    Route::get('/receivable-payment/{id}/print', [App\Http\Controllers\PaymentPrintController::class, 'printReceivable'])->name('receivable-payment.print');
    Route::get('/payable-payment/{id}/print', [App\Http\Controllers\PaymentPrintController::class, 'printPayable'])->name('payable-payment.print');

    Route::get('/payroll-period/{id}/payslips/pdf', [PayslipPdfController::class, 'downloadByPeriod'])->name('payslip.pdf.period');
    Route::get('/payslip/{id}/pdf', [PayslipPdfController::class, 'downloadSingle'])->name('payslip.pdf.single');

    // Route to update existing opening balance dates
    Route::post('/internal/opening-balances/update-dates', [OpeningBalanceUpdateController::class, 'updateDates'])->name('opening-balances.update-dates');

});
