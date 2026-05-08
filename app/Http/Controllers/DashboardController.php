<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalStock = Item::sum('stock');

        $lowStockItems = Item::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        $emptyStockItems = Item::where('stock', 0)->count();

        $totalStockIn = StockTransactionDetail::whereHas('transaction', function ($query) {
            $query->where('type', 'in');
        })->sum('quantity');

        $totalStockOut = StockTransactionDetail::whereHas('transaction', function ($query) {
            $query->where('type', 'out');
        })->sum('quantity');

        $latestTransactions = StockTransaction::with(['details.item'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockList = Item::with('category')
            ->where(function ($query) {
                $query->where('stock', 0)
                    ->orWhereColumn('stock', '<=', 'minimum_stock');
            })
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalItems',
            'totalCategories',
            'totalStock',
            'lowStockItems',
            'emptyStockItems',
            'totalStockIn',
            'totalStockOut',
            'latestTransactions',
            'lowStockList'
        ));
    }
}