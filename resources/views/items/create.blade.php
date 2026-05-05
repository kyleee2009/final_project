@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="page-header">
        <div>
            <h2>Tambah Barang</h2>
            <p>Tambahkan data barang baru ke dalam sistem gudang.</p>
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

        <form action="{{ route('items.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="item_code">Kode Barang</label>
                    <input 
                        type="text" 
                        id="item_code" 
                        name="item_code" 
                        class="form-control" 
                        value="{{ old('item_code') }}"
                        placeholder="Contoh: BRG-0001"
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
                        value="{{ old('barcode') }}"
                        placeholder="Scan / input barcode"
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
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kabel HDMI"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        value="{{ old('unit', 'pcs') }}"
                        placeholder="Contoh: pcs, box, meter"
                        required
                    >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="stock">Stok Awal</label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        class="form-control" 
                        value="{{ old('stock', 0) }}"
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
                        value="{{ old('minimum_stock', 0) }}"
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
                    value="{{ old('location') }}"
                    placeholder="Contoh: Rak A1"
                >
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control textarea"
                    placeholder="Keterangan tambahan barang"
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection