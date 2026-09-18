<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('sales.index');
});

// 1. Route untuk /sales (Laporan 1 - Folder sales / sales.laporan1)
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/{salesReport}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{salesReport}', [SalesController::class, 'destroy'])->name('sales.destroy');

// 2. Route untuk /tabel (Laporan 2 - Folder tabel / tabel.laporan2)
Route::get('/tabel', [SalesController::class, 'indexTabel'])->name('tabel.index');
Route::post('/tabel/transaction', [SalesController::class, 'storeTabelTransaction'])->name('tabel.transaction.store');
Route::post('/tabel/product', [SalesController::class, 'storeTabelProduct'])->name('tabel.product.store');
Route::put('/tabel/transaction', [SalesController::class, 'updateTabelTransaction'])->name('tabel.transaction.update');
Route::delete('/tabel/product', [SalesController::class, 'destroyTabelProduct'])->name('tabel.product.destroy');
