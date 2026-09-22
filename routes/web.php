<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('sales.index');
});

// Route Laporan Penjualan
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/{salesReport}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{salesReport}', [SalesController::class, 'destroy'])->name('sales.destroy');
