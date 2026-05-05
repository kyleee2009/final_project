@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="page-header">
        <div>
            <h2>Edit Barang</h2>
            <p>Perbarui data barang yang tersimpan di sistem gudang.</p>
        </div>

        <a href="{{ route('items.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="form-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.update', $item) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="item_code">Kode Barang</label>
                    <input 
                        type="text" 
                        id="item_code" 
                        name="item_code" 
                        class="form-control" 
                        value="{{ old('item_code', $item->item_code) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input 
                        type="text" 
                        id="barcode" 
                        name="barcode" 
                        class="form-control" 
                        value="{{ old('barcode', $item->barcode) }}"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="name">Nama Barang</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    value="{{ old('name', $item->name) }}"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="unit">Satuan</label>
                    <input 
                        type="text" 
                        id="unit" 
                        name="unit" 
                        class="form-control" 
                        value="{{ old('unit', $item->unit) }}"
                        required
                    >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="stock">Stok</label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        class="form-control" 
                        value="{{ old('stock', $item->stock) }}"
                        min="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="minimum_stock">Stok Minimum</label>
                    <input 
                        type="number" 
                        id="minimum_stock" 
                        name="minimum_stock" 
                        class="form-control" 
                        value="{{ old('minimum_stock', $item->minimum_stock) }}"
                        min="0"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="location">Lokasi Barang</label>
                <input 
                    type="text" 
                    id="location" 
                    name="location" 
                    class="form-control" 
                    value="{{ old('location', $item->location) }}"
                >
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control textarea"
                >{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection