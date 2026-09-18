<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::get('/', function () {
    return redirect()->route('sales.index');
});

// 1. Route untuk /sales (Menggunakan view folder sales)
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

// 2. Route untuk /tabel (Menggunakan view folder tabel)
Route::get('/tabel', [SalesController::class, 'indexTabel'])->name('tabel.index');

// Action POST, PUT, DELETE (Digunakan bersama oleh kedua website)
Route::post('/sales/product', [SalesController::class, 'storeProduct'])->name('sales.product.store');
Route::post('/sales/transaction', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/transaction/update', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/product/delete', [SalesController::class, 'destroy'])->name('sales.destroy');