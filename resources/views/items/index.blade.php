@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <div class="page-header">
        <div>
            <h2>Data Barang</h2>
            <p>Kelola seluruh barang yang tersedia di gudang.</p>
        </div>

        <a href="{{ route('items.create') }}" class="btn btn-primary">
            Tambah Barang
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

<div class="filter-card">
    <form action="{{ route('items.index') }}" method="GET">
        <div class="filter-row item-filter">
            <div class="form-group">
                <label for="search">Cari Barang</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="form-control" 
                    value="{{ request('search') }}"
                    placeholder="Cari kode, barcode, nama, kategori, atau lokasi"
                >
            </div>

            <div class="form-group">
                <label for="stock_status">Status Stok</label>
                <select id="stock_status" name="stock_status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('stock_status') == 'aman' ? 'selected' : '' }}>
                        Aman
                    </option>
                    <option value="menipis" {{ request('stock_status') == 'menipis' ? 'selected' : '' }}>
                        Stok Menipis
                    </option>
                    <option value="habis" {{ request('stock_status') == 'habis' ? 'selected' : '' }}>
                        Habis
                    </option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $item)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->barcode ?? '-' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->stock }} {{ $item->unit }}</td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td>
                           @if ($item->stock == 0)
                                <span class="badge badge-out">Habis</span>
                            @elseif ($item->stock <= $item->minimum_stock)
                                <span class="badge badge-warning">Stok Menipis</span>
                            @else
                                <span class="badge badge-in">Aman</span>
                            @endif
                        </td>
                        <td>
                           <div class="table-actions">
                                <a href="{{ route('items.barcode', $item) }}" class="btn btn-primary" target="_blank">
                                    Barcode
                                </a>

                                <a href="{{ route('items.edit', $item) }}" class="btn btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-text">
                            Belum ada data barang.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $items->links() }}
        </div>
    </div>
@endsection