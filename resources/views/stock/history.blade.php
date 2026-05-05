@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h2>Riwayat Transaksi Stok</h2>
            <p>Menampilkan seluruh riwayat barang masuk dan barang keluar.</p>
        </div>

        <div class="table-actions">
            <a href="{{ route('stock.in') }}" class="btn btn-primary">
                Barang Masuk
            </a>

            <a href="{{ route('stock.out') }}" class="btn btn-secondary">
                Barang Keluar
            </a>
        </div>
    </div>

    <div class="filter-card">
        <form action="{{ route('stock.history') }}" method="GET">
            <div class="filter-row transaction-filter">
                <div class="form-group">
                    <label for="search">Cari Transaksi</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        class="form-control" 
                        value="{{ request('search') }}"
                        placeholder="Cari kode transaksi, barang, barcode, atau tujuan"
                    >
                </div>

                <div class="form-group">
                    <label for="type">Jenis</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Tanggal Awal</label>
                    <input 
                        type="date" 
                        id="start_date" 
                        name="start_date" 
                        class="form-control" 
                        value="{{ request('start_date') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="end_date">Tanggal Akhir</label>
                    <input 
                        type="date" 
                        id="end_date" 
                        name="end_date" 
                        class="form-control" 
                        value="{{ request('end_date') }}"
                    >
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('stock.history') }}" class="btn btn-secondary">
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
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Sumber/Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }} WIB</td>
                        <td>{{ $transaction->item->name ?? '-' }}</td>
                        <td>
                            @if ($transaction->type == 'in')
                                <span class="badge badge-in">Masuk</span>
                            @else
                                <span class="badge badge-out">Keluar</span>
                            @endif
                        </td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->stock_before }}</td>
                        <td>{{ $transaction->stock_after }}</td>
                        <td>{{ $transaction->source_or_destination ?? '-' }}</td>
                        <td>
                            <a href="{{ route('stock.print', $transaction) }}" class="btn btn-primary" target="_blank">
                                Cetak
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="empty-text">
                            Data transaksi tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection