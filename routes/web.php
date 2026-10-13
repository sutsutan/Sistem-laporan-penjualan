<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman Utama -> Redirect ke Dashboard Sales
Route::get('/', function () {
    return redirect()->route('sales.index');
});

// Autentikasi (Tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

// Autentikasi (Pengguna Terdaftar)
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

// Rute Dashboard Laporan Penjualan (Hanya untuk pengguna yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::put('/sales/{salesReport}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{salesReport}', [SalesController::class, 'destroy'])->name('sales.destroy');

    // Manajemen Pengguna (Hanya untuk Administrator)
    Route::middleware('admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});
