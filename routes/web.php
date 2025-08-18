<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriLaporanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [UserController::class, 'showHome'])->name('dashboard');
    Route::get('kategori-laporan', [KategoriLaporanController::class, 'index'])->name('kategori-laporan');
    Route::get('kecamatan', [KecamatanController::class, 'index'])->name('kecamatan');
    Route::get('kelurahan', [KelurahanController::class, 'index'])->name('kelurahan');
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
});