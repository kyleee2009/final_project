<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function stockIn()
    {
        $items = Item::orderBy('name', 'asc')->get();

        return view('stock.in', compact('items'));
    }

    public function storeStockIn(Request $request)
    {
        $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'barcode' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'source_or_destination' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'quantity.required' => 'Jumlah barang masuk wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
        ]);

        if (!$request->item_id && !$request->barcode) {
            return back()
                ->withInput()
                ->withErrors(['item_id' => 'Pilih barang atau scan barcode terlebih dahulu.']);
        }

        $item = null;

        if ($request->item_id) {
            $item = Item::find($request->item_id);
        }

        if (!$item && $request->barcode) {
            $item = Item::where('barcode', $request->barcode)
                ->orWhere('item_code', $request->barcode)
                ->first();
        }

        if (!$item) {
            return back()
                ->withInput()
                ->withErrors(['barcode' => 'Barang tidak ditemukan. Pastikan barcode atau kode barang benar.']);
        }

        DB::transaction(function () use ($request, $item) {
            $stockBefore = $item->stock;
            $stockAfter = $stockBefore + $request->quantity;

            $item->update([
                'stock' => $stockAfter,
            ]);

            StockTransaction::create([
                'transaction_code' => $this->generateTransactionCode('in'),
                'item_id' => $item->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'type' => 'in',
                'quantity' => $request->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'source_or_destination' => $request->source_or_destination,
                'description' => $request->description,
                'transaction_date' => now(),
            ]);
        });

        return redirect()
            ->route('stock.in')
            ->with('success', 'Barang masuk berhasil disimpan dan stok berhasil diperbarui.');
    }

    public function stockOut()
    {
        return view('stock.out');
    }

    public function storeStockOut(Request $request)
    {
        // Nanti kita isi pada fitur Barang Keluar
    }

    public function history()
    {
        $transactions = StockTransaction::with(['item', 'user'])
            ->latest()
            ->paginate(10);

        return view('stock.history', compact('transactions'));
    }

    private function generateTransactionCode($type)
    {
        $prefix = $type === 'in' ? 'TRX-IN' : 'TRX-OUT';

        $lastTransaction = StockTransaction::where('type', $type)
            ->latest('id')
            ->first();

        $nextNumber = $lastTransaction ? $lastTransaction->id + 1 : 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}