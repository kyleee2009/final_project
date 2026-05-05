@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
    <div class="page-header">
        <div>
            <h2>Data Kategori Barang</h2>
            <p>Kelola kategori untuk mengelompokkan barang gudang.</p>
        </div>

        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            Tambah Kategori
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $index => $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $index }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td>{{ $category->items->count() }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                        <td colspan="5" class="empty-text">
                            Belum ada data kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $categories->links() }}
        </div>
    </div>
@endsection