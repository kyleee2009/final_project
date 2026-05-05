@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
    <div class="page-header">
        <div>
            <h2>Laporan Stok Barang</h2>
            <p>Menampilkan kondisi stok seluruh barang di gudang.</p>
        </div>

        <a href="{{ route('items.index') }}" class="btn btn-secondary">
            Data Barang
        </a>
    </div>

    <div class="card-grid">
        <div class="card blue">
            <div class="card-title">Total Barang</div>
            <div class="card-value">{{ $totalItems }}</div>
        </div>

        <div class="card green">
            <div class="card-title">Total Stok</div>
            <div class="card-value">{{ $totalStock }}</div>
        </div>

        <div class="card orange">
            <div class="card-title">Stok Menipis</div>
            <div class="card-value">{{ $lowStock }}</div>
        </div>

        <div class="card red">
            <div class="card-title">Stok Habis</div>
            <div class="card-value">{{ $emptyStock }}</div>
        </div>
    </div>

    <div class="filter-card">
        <form action="{{ route('reports.stock') }}" method="GET">
            <div class="filter-row">
                <div class="form-group">
                    <label for="search">Cari Barang</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        class="form-control" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama, kode, barcode, atau lokasi"
                    >
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status Stok</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('reports.stock') }}" class="btn btn-secondary">
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
                    <th>Kode Barang</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Lokasi</th>
                    <th>Status</th>
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
                        <td>{{ $item->minimum_stock }} {{ $item->unit }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-text">
                            Data stok tidak ditemukan.
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