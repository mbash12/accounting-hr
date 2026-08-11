<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Api;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\EmployeeApiAuthController;
use App\Http\Controllers\Api\EmployeeApiAttendanceController;
use App\Http\Controllers\Api\EmployeeApiPermitController;
use App\Http\Controllers\Api\EmployeeApiOvertimeController;
use App\Http\Controllers\Api\EmployeeApiUploadController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Middleware\EmployeeApiAuth;

use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\BiometricEmployeeController;
use App\Http\Controllers\Api\BiometricAttendanceController;
use App\Http\Controllers\Api\ElevateIntegrationController;

Route::prefix('master')->middleware([Api::class])->group(function () {
    Route::get('/coa', [MasterDataController::class, 'coa']);
    Route::get('/vendor', [MasterDataController::class, 'vendor']);
    Route::get('/products', [MasterDataController::class, 'itemmaster']);
    Route::get('/categories', [MasterDataController::class, 'categories']);
    Route::get('/department', [MasterDataController::class, 'department']);
    Route::get('/unit', [MasterDataController::class, 'unit']);
    Route::get('/taxes', [MasterDataController::class, 'taxes']);
    Route::get('/customers', [MasterDataController::class, 'customers']);
    Route::get('/companies', [MasterDataController::class, 'companies']);
    Route::post('/products', [MasterDataController::class, 'syncProducts']);
    Route::post('/sync-vendors', [MasterDataController::class, 'syncVendor']);
    Route::post('/sync-uom', [MasterDataController::class, 'syncUom']);
    Route::post('/sync-uom-categories', [MasterDataController::class, 'syncUomCategories']);
    Route::post('/sync-uom-conversions', [MasterDataController::class, 'syncUomConversions']);
});

Route::prefix('purchase')->middleware([Api::class])->group(function () {
    Route::post('/check', [PurchaseOrderController::class, 'detailPurchaseOrder']);
    Route::post('/store', [PurchaseOrderController::class, 'storePurchaseOrder']);
});


Route::prefix('employeeapi/auth')->group(function () {
    Route::post('/login', [EmployeeApiAuthController::class, 'login']);
    Route::middleware([EmployeeApiAuth::class])->group(function () {
        Route::get('/me', [EmployeeApiAuthController::class, 'me']);
        Route::post('/logout', [EmployeeApiAuthController::class, 'logout']);
    });
});

Route::prefix('employeeapi')->middleware([EmployeeApiAuth::class])->group(function () {
    Route::post('/upload', [EmployeeApiUploadController::class, 'store']);
    Route::get('/permits', [EmployeeApiPermitController::class, 'index']);
    Route::post('/permits', [EmployeeApiPermitController::class, 'store']);
    Route::get('/permits/quota', [EmployeeApiPermitController::class, 'quota']);
    Route::get('/permits/{permit}', [EmployeeApiPermitController::class, 'show']);
    Route::put('/permits/{permit}', [EmployeeApiPermitController::class, 'update']);
    Route::get('/attendances', [EmployeeApiAttendanceController::class, 'index']);
    Route::post('/attendances', [EmployeeApiAttendanceController::class, 'store']);
    Route::get('/attendances/{attendance}', [EmployeeApiAttendanceController::class, 'show']);
    Route::put('/attendances/{attendance}', [EmployeeApiAttendanceController::class, 'update']);
    Route::get('/overtimes', [EmployeeApiOvertimeController::class, 'index']);
    Route::post('/overtimes', [EmployeeApiOvertimeController::class, 'store']);
    Route::get('/overtimes/{overtimeLog}', [EmployeeApiOvertimeController::class, 'show']);
    Route::put('/overtimes/{overtimeLog}', [EmployeeApiOvertimeController::class, 'update']);
    Route::post('/attendances/clock', [EmployeeApiAttendanceController::class, 'clock']);
    Route::get('/faqs', [FaqController::class, 'index']);
});

// Biometric device broker endpoints (machine-to-machine, API key auth).
Route::prefix('v1/records')->middleware('api.key')->group(function () {
    Route::get('/employees', [BiometricEmployeeController::class, 'index']);
    Route::post('/employees', [BiometricEmployeeController::class, 'store']);
    Route::post('/clocks', [BiometricAttendanceController::class, 'store']);
});

Route::prefix('v3')->middleware('api.key')->group(function () {
    Route::post('/clear-log', [BiometricAttendanceController::class, 'clearLog']);
    Route::post('/clear-usr', [BiometricEmployeeController::class, 'clear']);
});


Route::prefix('integration')->middleware([Api::class])->group(function () {
    Route::post('/work-orders', [ElevateIntegrationController::class, 'processWorkOrder']);
});
