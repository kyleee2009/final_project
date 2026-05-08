<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'required|exists:items,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'source_or_destination' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'item_ids.required' => 'Minimal harus ada satu barang dalam transaksi.',
            'quantities.required' => 'Jumlah barang wajib diisi.',
            'quantities.*.required' => 'Jumlah barang wajib diisi.',
            'quantities.*.integer' => 'Jumlah barang harus berupa angka.',
            'quantities.*.min' => 'Jumlah barang minimal 1.',
        ]);

        $itemsData = $this->prepareItemsData($request);

        if (count($itemsData) === 0) {
            return back()
                ->withInput()
                ->withErrors(['item_ids' => 'Minimal harus ada satu barang yang valid.']);
        }

        DB::transaction(function () use ($request, $itemsData) {
            $transaction = StockTransaction::create([
                'transaction_code' => $this->generateTransactionCode('in'),
                'user_id' => auth()->check() ? auth()->id() : null,
                'type' => 'in',
                'source_or_destination' => $request->source_or_destination,
                'description' => $request->description,
                'transaction_date' => now(),
            ]);

            foreach ($itemsData as $itemId => $quantity) {
                $item = Item::where('id', $itemId)->lockForUpdate()->first();

                $stockBefore = $item->stock;
                $stockAfter = $stockBefore + $quantity;

                $item->update([
                    'stock' => $stockAfter,
                ]);

                StockTransactionDetail::create([
                    'stock_transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                ]);
            }
        });

        return redirect()
            ->route('stock.in')
            ->with('success', 'Transaksi barang masuk berhasil disimpan.');
    }

    public function stockOut()
    {
        $items = Item::orderBy('name', 'asc')->get();

        return view('stock.out', compact('items'));
    }

    public function storeStockOut(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'required|exists:items,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'source_or_destination' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'item_ids.required' => 'Minimal harus ada satu barang dalam transaksi.',
            'quantities.required' => 'Jumlah barang wajib diisi.',
            'quantities.*.required' => 'Jumlah barang wajib diisi.',
            'quantities.*.integer' => 'Jumlah barang harus berupa angka.',
            'quantities.*.min' => 'Jumlah barang minimal 1.',
        ]);

        $itemsData = $this->prepareItemsData($request);

        if (count($itemsData) === 0) {
            return back()
                ->withInput()
                ->withErrors(['item_ids' => 'Minimal harus ada satu barang yang valid.']);
        }

        DB::transaction(function () use ($request, $itemsData) {
            $transaction = StockTransaction::create([
                'transaction_code' => $this->generateTransactionCode('out'),
                'user_id' => auth()->check() ? auth()->id() : null,
                'type' => 'out',
                'source_or_destination' => $request->source_or_destination,
                'description' => $request->description,
                'transaction_date' => now(),
            ]);

            foreach ($itemsData as $itemId => $quantity) {
                $item = Item::where('id', $itemId)->lockForUpdate()->first();

                if ($item->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'quantities' => 'Stok barang "' . $item->name . '" tidak mencukupi. Stok tersedia hanya ' . $item->stock . ' ' . $item->unit . '.',
                    ]);
                }

                $stockBefore = $item->stock;
                $stockAfter = $stockBefore - $quantity;

                $item->update([
                    'stock' => $stockAfter,
                ]);

                StockTransactionDetail::create([
                    'stock_transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                ]);
            }
        });

        return redirect()
            ->route('stock.out')
            ->with('success', 'Transaksi barang keluar berhasil disimpan.');
    }

    public function history(Request $request)
    {
        $query = StockTransaction::with(['details.item', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', '%' . $search . '%')
                    ->orWhere('source_or_destination', 'like', '%' . $search . '%')
                    ->orWhereHas('details.item', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('item_code', 'like', '%' . $search . '%')
                            ->orWhere('barcode', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock.history', compact('transactions'));
    }

    public function printReceipt(StockTransaction $transaction)
    {
        $transaction->load(['details.item.category', 'user']);

        return view('stock.print', compact('transaction'));
    }

    private function prepareItemsData(Request $request)
    {
        $itemsData = [];

        foreach ($request->item_ids as $index => $itemId) {
            if (!$itemId) {
                continue;
            }

            $quantity = $request->quantities[$index] ?? 0;

            if ($quantity < 1) {
                continue;
            }

            if (!isset($itemsData[$itemId])) {
                $itemsData[$itemId] = 0;
            }

            $itemsData[$itemId] += (int) $quantity;
        }

        return $itemsData;
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