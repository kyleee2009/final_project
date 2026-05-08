@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-welcome">
        <div>
            <h2>Dashboard Sistem Gudang</h2>
            <p>Ringkasan pencatatan barang masuk, barang keluar, dan kondisi stok gudang.</p>
        </div>

        <div class="dashboard-date">
            {{ now()->format('d-m-Y H:i') }} WIB
        </div>
    </div>

    <div class="card-grid">
        <div class="card blue">
            <div class="card-title">Total Barang</div>
            <div class="card-value">{{ $totalItems }}</div>
        </div>

        <div class="card green">
            <div class="card-title">Total Kategori</div>
            <div class="card-value">{{ $totalCategories }}</div>
        </div>

        <div class="card orange">
            <div class="card-title">Total Stok</div>
            <div class="card-value">{{ $totalStock }}</div>
        </div>

        <div class="card red">
            <div class="card-title">Stok Menipis</div>
            <div class="card-value">{{ $lowStockItems }}</div>
        </div>
    </div>

    <div class="card-grid">
        <div class="card green">
            <div class="card-title">Total Barang Masuk</div>
            <div class="card-value">{{ $totalStockIn }}</div>
        </div>

        <div class="card red">
            <div class="card-title">Total Barang Keluar</div>
            <div class="card-value">{{ $totalStockOut }}</div>
        </div>

        <div class="card orange">
            <div class="card-title">Stok Habis</div>
            <div class="card-value">{{ $emptyStockItems }}</div>
        </div>

        <div class="card blue">
            <div class="card-title">Status Sistem</div>
            <div class="card-value small-value">Aktif</div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('items.create') }}" class="quick-card">
            <span>+</span>
            <div>
                <h3>Tambah Barang</h3>
                <p>Input data barang baru ke gudang.</p>
            </div>
        </a>

        <a href="{{ route('stock.in') }}" class="quick-card">
            <span>↓</span>
            <div>
                <h3>Barang Masuk</h3>
                <p>Catat beberapa barang yang masuk.</p>
            </div>
        </a>

        <a href="{{ route('stock.out') }}" class="quick-card">
            <span>↑</span>
            <div>
                <h3>Barang Keluar</h3>
                <p>Catat beberapa barang yang keluar.</p>
            </div>
        </a>

        <a href="{{ route('reports.stock') }}" class="quick-card">
            <span>!</span>
            <div>
                <h3>Laporan Stok</h3>
                <p>Lihat kondisi stok barang.</p>
            </div>
        </a>
    </div>

    <div class="dashboard-grid">
        <div class="table-card">
            <div class="section-header">
                <h2>Transaksi Terbaru</h2>
                <a href="{{ route('stock.history') }}" class="section-link">Lihat Semua</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Jumlah Item</th>
                        <th>Total Qty</th>
                        <th>Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_code }}</td>
                            <td>
                                @if ($transaction->type == 'in')
                                    <span class="badge badge-in">Masuk</span>
                                @else
                                    <span class="badge badge-out">Keluar</span>
                                @endif
                            </td>
                            <td>{{ $transaction->details->count() }} jenis</td>
                            <td>{{ $transaction->details->sum('quantity') }}</td>
                            <td>
                                <a href="{{ route('stock.print', $transaction) }}" class="btn btn-primary" target="_blank">
                                    Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-text">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-card">
            <div class="section-header">
                <h2>Stok Perlu Perhatian</h2>
                <a href="{{ route('reports.stock', ['status' => 'menipis']) }}" class="section-link">Detail</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockList as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <span class="muted-text">{{ $item->category->name ?? '-' }}</span>
                            </td>
                            <td>{{ $item->stock }} {{ $item->unit }}</td>
                            <td>
                                @if ($item->stock == 0)
                                    <span class="badge badge-out">Habis</span>
                                @else
                                    <span class="badge badge-warning">Menipis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-text">
                                Tidak ada stok bermasalah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection