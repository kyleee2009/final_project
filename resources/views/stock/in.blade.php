@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    <div class="page-header">
        <div>
            <h2>Barang Masuk</h2>
            <p>Catat barang yang masuk ke gudang dan stok akan bertambah otomatis.</p>
        </div>

        <a href="{{ route('stock.history') }}" class="btn btn-secondary">
            Lihat Riwayat
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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

        <form action="{{ route('stock.in.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="barcode">Scan Barcode / Kode Barang</label>
                <input 
                    type="text" 
                    id="barcode" 
                    name="barcode" 
                    class="form-control" 
                    value="{{ old('barcode') }}"
                    placeholder="Scan barcode atau ketik kode barang"
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="item_id">Pilih Barang</label>
                <select id="item_id" name="item_id" class="form-control">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($items as $item)
                        <option 
                            value="{{ $item->id }}"
                            data-barcode="{{ $item->barcode }}"
                            data-code="{{ $item->item_code }}"
                            data-stock="{{ $item->stock }}"
                            data-unit="{{ $item->unit }}"
                            {{ old('item_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->item_code }} - {{ $item->name }} | Stok: {{ $item->stock }} {{ $item->unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="current_stock">Stok Saat Ini</label>
                    <input 
                        type="text" 
                        id="current_stock" 
                        class="form-control" 
                        value="-"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah Masuk</label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        class="form-control" 
                        value="{{ old('quantity', 1) }}"
                        min="1"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="source_or_destination">Sumber Barang / Supplier</label>
                <input 
                    type="text" 
                    id="source_or_destination" 
                    name="source_or_destination" 
                    class="form-control" 
                    value="{{ old('source_or_destination') }}"
                    placeholder="Contoh: Supplier A / Pembelian toko"
                >
            </div>

            <div class="form-group">
                <label for="description">Keterangan</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control textarea"
                    placeholder="Keterangan tambahan"
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Simpan Barang Masuk
                </button>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const barcodeInput = document.getElementById('barcode');
        const itemSelect = document.getElementById('item_id');
        const currentStockInput = document.getElementById('current_stock');

        function updateCurrentStock() {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];

            if (!selectedOption || !selectedOption.value) {
                currentStockInput.value = '-';
                return;
            }

            const stock = selectedOption.getAttribute('data-stock');
            const unit = selectedOption.getAttribute('data-unit');

            currentStockInput.value = stock + ' ' + unit;
        }

        itemSelect.addEventListener('change', updateCurrentStock);

        barcodeInput.addEventListener('input', function () {
            const scannedValue = barcodeInput.value.trim();

            if (scannedValue === '') {
                return;
            }

            for (let i = 0; i < itemSelect.options.length; i++) {
                const option = itemSelect.options[i];
                const barcode = option.getAttribute('data-barcode');
                const code = option.getAttribute('data-code');

                if (barcode === scannedValue || code === scannedValue) {
                    itemSelect.value = option.value;
                    updateCurrentStock();
                    break;
                }
            }
        });

        updateCurrentStock();
    </script>
@endsection