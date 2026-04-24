<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockTransactionController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/categories', CategoryController::class);
Route::resource('/items', ItemController::class);

Route::get('/stock/in', [StockTransactionController::class, 'stockIn'])->name('stock.in');
Route::post('/stock/in', [StockTransactionController::class, 'storeStockIn'])->name('stock.in.store');

Route::get('/stock/out', [StockTransactionController::class, 'stockOut'])->name('stock.out');
Route::post('/stock/out', [StockTransactionController::class, 'storeStockOut'])->name('stock.out.store');

Route::get('/stock/history', [StockTransactionController::class, 'history'])->name('stock.history');