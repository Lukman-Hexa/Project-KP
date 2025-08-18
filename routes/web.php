<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriLaporanController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [UserController::class, 'showHome'])->name('dashboard');
    Route::get('kategori-laporan', [KategoriLaporanController::class, 'index'])->name('kategori-laporan');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('kategori-laporan', [KategoriLaporanController::class, 'all']);
    Route::post('kategori-laporan', [KategoriLaporanController::class, 'store']);
    Route::put('kategori-laporan/{id}', [KategoriLaporanController::class, 'update']);
    Route::delete('kategori-laporan/{id}', [KategoriLaporanController::class, 'destroy']);
});