<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
Route::get('/lapangan/{id}', [LapanganController::class, 'show'])->name('lapangan.show');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/kontak', 'pages.kontak')->name('kontak');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin & Petugas Routes
Route::middleware(['auth', 'role:admin,petugas'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('lapangan', \App\Http\Controllers\Admin\LapanganController::class);
    Route::delete('lapangan/foto/{id}', [\App\Http\Controllers\Admin\LapanganController::class, 'deleteFoto'])->name('lapangan.foto.destroy');
    
    Route::get('slot', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'index'])->name('slot.index');
    Route::post('slot', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'store'])->name('slot.store');
    Route::delete('slot/{slot}', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'destroy'])->name('slot.destroy');
    Route::post('slot/generate', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'generate'])->name('slot.generate');
    Route::patch('slot/{slot}/status', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'updateStatus'])->name('slot.status');
    Route::post('slot/bulk', [\App\Http\Controllers\Admin\SlotWaktuController::class, 'bulkUpdate'])->name('slot.bulk');
    
    Route::get('booking', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('booking.index');
    Route::get('booking/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('booking.show');
    Route::post('booking/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('booking/{booking}/reject', [\App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('booking.reject');
    Route::post('booking/{booking}/complete', [\App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('booking.complete');
    Route::patch('booking/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('booking.update-status');
    
    Route::get('pembayaran', [\App\Http\Controllers\Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('pembayaran/{booking}/verify', [\App\Http\Controllers\Admin\PembayaranController::class, 'verify'])->name('pembayaran.verify');
    
    Route::get('statistik', [\App\Http\Controllers\Admin\StatistikGolController::class, 'index'])->name('statistik.index');
    Route::post('statistik', [\App\Http\Controllers\Admin\StatistikGolController::class, 'store'])->name('statistik.store');
    Route::delete('statistik/{statistik}', [\App\Http\Controllers\Admin\StatistikGolController::class, 'destroy'])->name('statistik.destroy');
    
    Route::get('leaderboard', [\App\Http\Controllers\Admin\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::post('leaderboard', [\App\Http\Controllers\Admin\LeaderboardController::class, 'store'])->name('leaderboard.store');
    Route::delete('leaderboard/{nama_pemain}', [\App\Http\Controllers\Admin\LeaderboardController::class, 'destroy'])->name('leaderboard.destroy');
    
    Route::get('qrscan', [\App\Http\Controllers\Admin\QrScanController::class, 'index'])->name('qrscan.index');
    Route::post('qrscan/process', [\App\Http\Controllers\Admin\QrScanController::class, 'process'])->name('qrscan.process');
    
    // Khusus Admin Utama
    Route::middleware('role:admin')->group(function () {
        Route::get('user', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.index');
        Route::get('laporan/penyewaan', [\App\Http\Controllers\Admin\LaporanController::class, 'penyewaan'])->name('laporan.penyewaan');
        Route::get('laporan/penyewaan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'penyewaanExcel'])->name('laporan.penyewaan.excel');
        Route::get('laporan/pendapatan', [\App\Http\Controllers\Admin\LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
        Route::get('laporan/pendapatan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'pendapatanExcel'])->name('laporan.pendapatan.excel');
        Route::get('pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
    });
});

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Member\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('profil', [\App\Http\Controllers\Member\ProfilController::class, 'index'])->name('profil.index');
    Route::put('profil', [\App\Http\Controllers\Member\ProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [\App\Http\Controllers\Member\ProfilController::class, 'updatePassword'])->name('profil.password');
    
    Route::get('booking', [\App\Http\Controllers\Member\BookingController::class, 'index'])->name('booking.index');
    Route::get('booking/create', [\App\Http\Controllers\Member\BookingController::class, 'create'])->name('booking.create');
    Route::post('booking/slots/lock', [\App\Http\Controllers\Member\BookingController::class, 'lockSlots'])->name('booking.slots.lock');
    Route::post('booking', [\App\Http\Controllers\Member\BookingController::class, 'store'])->name('booking.store');
    Route::get('booking/{booking}', [\App\Http\Controllers\Member\BookingController::class, 'show'])->name('booking.show');
    Route::get('booking/{booking}/checkout', [\App\Http\Controllers\Member\BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('booking/{booking}/pay', [\App\Http\Controllers\Member\BookingController::class, 'pay'])->name('booking.pay');
    Route::post('booking/{booking}/cancel', [\App\Http\Controllers\Member\BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('booking/{booking}/reschedule', [\App\Http\Controllers\Member\BookingController::class, 'rescheduleForm'])->name('booking.reschedule');
    Route::post('booking/{booking}/reschedule', [\App\Http\Controllers\Member\BookingController::class, 'reschedule'])->name('booking.reschedule.store');
    
    Route::post('booking/{booking}/payment/manual', [\App\Http\Controllers\Payment\ManualPaymentController::class, 'uploadBukti'])->name('booking.payment.manual');
    Route::post('booking/{booking}/rating', [\App\Http\Controllers\Member\RatingController::class, 'store'])->name('booking.rating');
    
    Route::get('notifikasi', [\App\Http\Controllers\Member\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/{notifikasi}/read', [\App\Http\Controllers\Member\NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('notifikasi/read-all', [\App\Http\Controllers\Member\NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.readAll');
});
