<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\StockTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalStock = Item::sum('stock');

        $lowStockItems = Item::whereColumn('stock', '<=', 'minimum_stock')->count();

        $totalStockIn = StockTransaction::where('type', 'in')->sum('quantity');
        $totalStockOut = StockTransaction::where('type', 'out')->sum('quantity');

        $latestTransactions = StockTransaction::with('item')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalItems',
            'totalCategories',
            'totalStock',
            'lowStockItems',
            'totalStockIn',
            'totalStockOut',
            'latestTransactions'
        ));
    }
}