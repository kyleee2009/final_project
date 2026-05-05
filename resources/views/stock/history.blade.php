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
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-text">
                            Belum ada riwayat transaksi.
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