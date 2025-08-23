<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriLaporanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicLaporanController;

// Rute untuk halaman publik
Route::get('/', [PublicController::class, 'index']);
Route::get('/laporan', [PublicLaporanController::class, 'index'])->name('public.laporan'); // Perbarui ini

// Rute untuk halaman admin
Route::get('admin', [UserController::class, 'showLoginForm'])->name('login');
Route::post('admin', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [UserController::class, 'showHome'])->name('dashboard');
    Route::get('kategori-laporan', [KategoriLaporanController::class, 'index'])->name('kategori-laporan');
    Route::get('kecamatan', [KecamatanController::class, 'index'])->name('kecamatan');
    Route::get('kelurahan', [KelurahanController::class, 'index'])->name('kelurahan');
    Route::get('input-laporan', [LaporanController::class, 'create'])->name('input-laporan');
    Route::get('data-laporan', [LaporanController::class, 'index'])->name('data-laporan');
    
    
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('kategori-laporan', [KategoriLaporanController::class, 'all']);
    Route::post('kategori-laporan', [KategoriLaporanController::class, 'store']);
    Route::put('kategori-laporan/{id}', [KategoriLaporanController::class, 'update']);
    Route::delete('kategori-laporan/{id}', [KategoriLaporanController::class, 'destroy']);

    // API untuk halaman Kecamatan
    Route::get('kecamatan', [KecamatanController::class, 'all']);
    Route::post('kecamatan', [KecamatanController::class, 'store']);
    Route::put('kecamatan/{id}', [KecamatanController::class, 'update']);
    Route::delete('kecamatan/{id}', [KecamatanController::class, 'destroy']);

    // API untuk halaman Kelurahan
    Route::get('kelurahan', [KelurahanController::class, 'all']);
    Route::post('kelurahan', [KelurahanController::class, 'store']);
    Route::put('kelurahan/{id}', [KelurahanController::class, 'update']);
    Route::delete('kelurahan/{id}', [KelurahanController::class, 'destroy']);

    
    // API untuk halaman input laporan
    // API untuk mengambil kelurahan berdasarkan kecamatan
    Route::get('kelurahan-by-kecamatan/{kecamatanId}', [LaporanController::class, 'getKelurahanByKecamatan']);
    
    // API untuk mengambil semua kecamatan
    // Route::get('kecamatan-all', [KecamatanController::class, 'all']);
    
    // API untuk mengambil semua kategori laporan
    Route::get('kategori-laporan-all', [KategoriLaporanController::class, 'all']);

    // Rute POST untuk menyimpan laporan
    Route::post('laporan', [LaporanController::class, 'store'])->name('laporan.store');

    // API untuk halaman data laporan
    Route::get('laporan', [LaporanController::class, 'all']); // Anda bisa abaikan ini, karena kita akan langsung passing data dari controller
    Route::get('laporan/{id}', [LaporanController::class, 'show']);
    Route::put('laporan/{id}', [LaporanController::class, 'update']);
    Route::delete('laporan/{id}', [LaporanController::class, 'destroy']);
    Route::post('laporan', [LaporanController::class, 'store'])->name('laporan.store');

});