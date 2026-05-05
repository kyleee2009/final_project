<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/categories', CategoryController::class);
    Route::get('/items/{item}/barcode-label', [ItemController::class, 'barcodeLabel'])->name('items.barcode');
    Route::resource('/items', ItemController::class);

    Route::get('/stock/in', [StockTransactionController::class, 'stockIn'])->name('stock.in');
    Route::post('/stock/in', [StockTransactionController::class, 'storeStockIn'])->name('stock.in.store');

    Route::get('/stock/out', [StockTransactionController::class, 'stockOut'])->name('stock.out');
    Route::post('/stock/out', [StockTransactionController::class, 'storeStockOut'])->name('stock.out.store');

    Route::get('/stock/history', [StockTransactionController::class, 'history'])->name('stock.history');
    Route::get('/stock/transactions/{transaction}/print', [StockTransactionController::class, 'printReceipt'])->name('stock.print');

    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});