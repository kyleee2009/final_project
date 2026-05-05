<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->latest()->paginate(10);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'item_code' => 'required|string|max:255|unique:items,item_code',
            'barcode' => 'nullable|string|max:255|unique:items,barcode',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'item_code.required' => 'Kode barang wajib diisi.',
            'item_code.unique' => 'Kode barang sudah digunakan.',
            'barcode.unique' => 'Barcode sudah digunakan.',
            'name.required' => 'Nama barang wajib diisi.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'minimum_stock.required' => 'Stok minimum wajib diisi.',
        ]);

        Item::create([
            'category_id' => $request->category_id,
            'item_code' => $request->item_code,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'unit' => $request->unit,
            'stock' => $request->stock,
            'minimum_stock' => $request->minimum_stock,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        return redirect()->route('items.edit', $item);
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'item_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'item_code')->ignore($item->id),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('items', 'barcode')->ignore($item->id),
            ],
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'item_code.required' => 'Kode barang wajib diisi.',
            'item_code.unique' => 'Kode barang sudah digunakan.',
            'barcode.unique' => 'Barcode sudah digunakan.',
            'name.required' => 'Nama barang wajib diisi.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'minimum_stock.required' => 'Stok minimum wajib diisi.',
        ]);

        $item->update([
            'category_id' => $request->category_id,
            'item_code' => $request->item_code,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'unit' => $request->unit,
            'stock' => $request->stock,
            'minimum_stock' => $request->minimum_stock,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
        
    }
public function barcodeLabel(Item $item)
{
    $barcodeValue = $item->barcode ?: $item->item_code;

    $generator = new BarcodeGeneratorPNG();

    $barcodeImage = base64_encode(
        $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128, 2, 60)
    );

    return view('items.barcode-label', compact('item', 'barcodeValue', 'barcodeImage'));
}
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}