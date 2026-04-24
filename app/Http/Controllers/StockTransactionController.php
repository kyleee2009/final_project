<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function stockIn()
    {
        return view('stock.in');
    }

    public function storeStockIn(Request $request)
    {
        // Nanti kita isi untuk proses barang masuk
    }

    public function stockOut()
    {
        return view('stock.out');
    }

    public function storeStockOut(Request $request)
    {
        // Nanti kita isi untuk proses barang keluar
    }

    public function history()
    {
        return view('stock.history');
    }
}