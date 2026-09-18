<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('sales.index');
});

// 1. Route untuk /sales (Menggunakan view folder sales)
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
<<<<<<< HEAD
Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/{salesReport}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{salesReport}', [SalesController::class, 'destroy'])->name('sales.destroy');
=======

// 2. Route untuk /tabel (Menggunakan view folder tabel)
Route::get('/tabel', [SalesController::class, 'indexTabel'])->name('tabel.index');

// Action POST, PUT, DELETE (Digunakan bersama oleh kedua website)
Route::post('/sales/product', [SalesController::class, 'storeProduct'])->name('sales.product.store');
Route::post('/sales/transaction', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/transaction/update', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/product/delete', [SalesController::class, 'destroy'])->name('sales.destroy');
>>>>>>> f9578cadb6990dc97c7b33d85b1511004d2fdeba
