@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
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
    </div>

    <div class="table-card">
        <h2>Transaksi Terbaru</h2>

        <table>
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $transaction->item->name ?? '-' }}</td>
                        <td>
                            @if ($transaction->type == 'in')
                                <span class="badge badge-in">Masuk</span>
                            @else
                                <span class="badge badge-out">Keluar</span>
                            @endif
                        </td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->stock_after }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection