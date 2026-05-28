<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Midtrans Webhook (No CSRF needed here usually, handled via VerifyCsrfToken exclusion)
Route::post('/payment/midtrans/notification', [\App\Http\Controllers\Payment\MidtransController::class, 'callback'])->name('api.payment.midtrans');

// Public API for Slots (AJAX calls)
Route::get('/lapangan/{id}/slots/{tanggal}', [\App\Http\Controllers\LapanganController::class, 'getSlots'])->name('api.lapangan.slots');

// Admin QR Scanner API
Route::middleware(['auth:sanctum', 'role:admin,petugas'])->group(function () {
    Route::post('/admin/qr-validate', [\App\Http\Controllers\Admin\QrScanController::class, 'validate_qr'])->name('api.admin.qrvalidate');
});
