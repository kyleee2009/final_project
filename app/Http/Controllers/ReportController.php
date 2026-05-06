<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = $this->buildStockQuery($request);

        $items = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name', 'asc')->get();

        $totalItems = Item::count();
        $totalStock = Item::sum('stock');
        $emptyStock = Item::where('stock', 0)->count();
        $lowStock = Item::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        return view('reports.stock', compact(
            'items',
            'categories',
            'totalItems',
            'totalStock',
            'emptyStock',
            'lowStock'
        ));
    }

    public function printStock(Request $request)
    {
        $items = $this->buildStockQuery($request)
            ->orderBy('name', 'asc')
            ->get();

        $totalItems = $items->count();
        $totalStock = $items->sum('stock');
        $emptyStock = $items->where('stock', 0)->count();
        $lowStock = $items->filter(function ($item) {
            return $item->stock > 0 && $item->stock <= $item->minimum_stock;
        })->count();

        return view('reports.stock-print', compact(
            'items',
            'totalItems',
            'totalStock',
            'emptyStock',
            'lowStock'
        ));
    }

    private function buildStockQuery(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', '%' . $search . '%')
                    ->orWhere('barcode', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'habis') {
                $query->where('stock', 0);
            }

            if ($request->status === 'menipis') {
                $query->where('stock', '>', 0)
                    ->whereColumn('stock', '<=', 'minimum_stock');
            }

            if ($request->status === 'aman') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            }
        }

        return $query;
    }
}