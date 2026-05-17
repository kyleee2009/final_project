@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    <div class="page-header">
        <div>
            <h2>Barang Masuk</h2>
            <p>Catat beberapa barang yang masuk ke gudang dalam satu transaksi.</p>
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

    <div class="form-card wide-form">
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

        <form action="{{ route('stock.in.store') }}" method="POST" id="stockInForm">
            @csrf

            <div class="scan-card">
                <h3>Tambah Barang ke Transaksi</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="barcode_input">Scan Barcode / Kode Barang</label>
                        <input 
                            type="text" 
                            id="barcode_input" 
                            class="form-control" 
                            placeholder="Scan barcode atau ketik kode barang"
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="item_select">Pilih Barang Manual</label>
                        <select id="item_select" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($items as $item)
                                <option 
                                    value="{{ $item->id }}"
                                    data-code="{{ $item->item_code }}"
                                    data-barcode="{{ $item->barcode }}"
                                    data-name="{{ $item->name }}"
                                    data-stock="{{ $item->stock }}"
                                    data-unit="{{ $item->unit }}"
                                >
                                    {{ $item->item_code }} - {{ $item->name }} | Stok: {{ $item->stock }} {{ $item->unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="selected_item_info">Detail Barang</label>
                        <input 
                            type="text" 
                            id="selected_item_info" 
                            class="form-control" 
                            value="-"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <label for="quantity_input">Jumlah Masuk</label>
                        <input 
                            type="number" 
                            id="quantity_input" 
                            class="form-control" 
                            value="1"
                            min="1"
                        >
                    </div>
                </div>

                <button type="button" class="btn btn-primary" id="addItemBtn">
                    Tambah ke Daftar
                </button>
            </div>

            <div class="table-card selected-items-card">
                <h2>Daftar Barang Masuk</h2>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok Saat Ini</th>
                            <th>Jumlah Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="selectedItemsBody">
                        <tr id="emptyRow">
                            <td colspan="6" class="empty-text">
                                Belum ada barang yang ditambahkan.
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                    Simpan Transaksi Barang Masuk
                </button>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Modal Barang Tidak Ditemukan --}}
    <div id="notFoundModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:28px; width:340px; text-align:center; box-shadow:0 8px 32px rgba(0,0,0,0.2);">
            <div style="font-size:40px; margin-bottom:12px;">⚠️</div>
            <h3 style="margin:0 0 8px; font-size:16px;">Barang Tidak Ditemukan</h3>
            <p style="font-size:13px; color:#555; margin-bottom:6px;">
                Barcode yang discan tidak terdaftar di database:
            </p>
            <div id="modalBarcodeValue" style="font-size:14px; font-weight:bold; background:#f3f4f6; padding:8px 12px; border-radius:8px; margin-bottom:20px; word-break:break-all;">
                -
            </div>
            <p style="font-size:12px; color:#888; margin-bottom:20px;">
                Apakah ingin mendaftarkan barang baru dengan barcode ini?
            </p>
            <div style="display:flex; gap:10px; justify-content:center;">
                <a id="addNewItemBtn" href="#" class="btn btn-primary" style="font-size:13px; padding:9px 16px;">
                    + Tambah Barang Baru
                </a>
                <button onclick="closeNotFoundModal()" class="btn btn-secondary" style="font-size:13px; padding:9px 16px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <script>
            const barcodeInput = document.getElementById('barcode_input');
            const itemSelect = document.getElementById('item_select');
            const selectedItemInfo = document.getElementById('selected_item_info');
            const quantityInput = document.getElementById('quantity_input');
            const addItemBtn = document.getElementById('addItemBtn');
            const selectedItemsBody = document.getElementById('selectedItemsBody');
            const emptyRow = document.getElementById('emptyRow');
            const stockInForm = document.getElementById('stockInForm');
            const notFoundModal = document.getElementById('notFoundModal');
            const modalBarcodeValue = document.getElementById('modalBarcodeValue');
            const addNewItemBtn = document.getElementById('addNewItemBtn');

            let selectedItems = {};

            // ===== MODAL FUNCTIONS =====
            function showNotFoundModal(scannedValue) {
                modalBarcodeValue.textContent = scannedValue;
                addNewItemBtn.href = '{{ route('items.create') }}?barcode=' + encodeURIComponent(scannedValue);
                notFoundModal.style.display = 'flex';
            }

            function closeNotFoundModal() {
                notFoundModal.style.display = 'none';
                barcodeInput.value = '';
                barcodeInput.focus();
            }

            // Tutup modal jika klik area luar
            notFoundModal.addEventListener('click', function (e) {
                if (e.target === notFoundModal) closeNotFoundModal();
            });

            // ===== ITEM FUNCTIONS =====
            function getSelectedOption() {
                return itemSelect.options[itemSelect.selectedIndex];
            }

            function updateSelectedItemInfo() {
                const option = getSelectedOption();

                if (!option || !option.value) {
                    selectedItemInfo.value = '-';
                    return;
                }

                const code = option.getAttribute('data-code');
                const name = option.getAttribute('data-name');
                const stock = option.getAttribute('data-stock');
                const unit = option.getAttribute('data-unit');

                selectedItemInfo.value = `${code} - ${name} | Stok saat ini: ${stock} ${unit}`;
            }

            function findItemByCode(codeValue) {
                const scannedValue = codeValue.trim();

                if (scannedValue === '') return false;

                for (let i = 0; i < itemSelect.options.length; i++) {
                    const option = itemSelect.options[i];
                    const barcode = option.getAttribute('data-barcode');
                    const code = option.getAttribute('data-code');

                    if (barcode === scannedValue || code === scannedValue) {
                        itemSelect.value = option.value;
                        updateSelectedItemInfo();
                        quantityInput.focus();
                        quantityInput.select();
                        return true;
                    }
                }

                // Tidak ditemukan → tampilkan modal
                selectedItemInfo.value = 'Barang tidak ditemukan';
                showNotFoundModal(scannedValue);
                return false;
            }

            function addItemToList() {
                const option = getSelectedOption();

                if (!option || !option.value) {
                    alert('Pilih barang atau input kode barang terlebih dahulu.');
                    barcodeInput.focus();
                    return;
                }

                const itemId = option.value;
                const quantity = parseInt(quantityInput.value);

                if (!quantity || quantity < 1) {
                    alert('Jumlah barang masuk minimal 1.');
                    quantityInput.focus();
                    return;
                }

                const item = {
                    id: itemId,
                    code: option.getAttribute('data-code'),
                    barcode: option.getAttribute('data-barcode'),
                    name: option.getAttribute('data-name'),
                    stock: option.getAttribute('data-stock'),
                    unit: option.getAttribute('data-unit'),
                    quantity: quantity,
                };

                if (selectedItems[itemId]) {
                    selectedItems[itemId].quantity += quantity;
                } else {
                    selectedItems[itemId] = item;
                }

                renderSelectedItems();

                barcodeInput.value = '';
                itemSelect.value = '';
                quantityInput.value = 1;
                selectedItemInfo.value = '-';
                barcodeInput.focus();
            }

            function removeItem(itemId) {
                delete selectedItems[itemId];
                renderSelectedItems();
            }

            function updateItemQuantity(itemId, value) {
                const quantity = parseInt(value);

                if (!quantity || quantity < 1) {
                    selectedItems[itemId].quantity = 1;
                } else {
                    selectedItems[itemId].quantity = quantity;
                }

                renderSelectedItems();
            }

            function renderSelectedItems() {
                const itemValues = Object.values(selectedItems);
                selectedItemsBody.innerHTML = '';

                if (itemValues.length === 0) {
                    selectedItemsBody.innerHTML = `
                        <tr id="emptyRow">
                            <td colspan="6" class="empty-text">
                                Belum ada barang yang ditambahkan.
                            </td>
                        </tr>
                    `;
                    return;
                }

                itemValues.forEach((item, index) => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>
                            ${item.code}
                            <input type="hidden" name="item_ids[]" value="${item.id}">
                        </td>
                        <td>${item.name}</td>
                        <td>${item.stock} ${item.unit}</td>
                        <td>
                            <input 
                                type="number" 
                                name="quantities[]" 
                                class="form-control table-input" 
                                value="${item.quantity}" 
                                min="1"
                                onchange="updateItemQuantity('${item.id}', this.value)"
                            >
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="removeItem('${item.id}')">
                                Hapus
                            </button>
                        </td>
                    `;

                    selectedItemsBody.appendChild(row);
                });
            }

            itemSelect.addEventListener('change', updateSelectedItemInfo);

            barcodeInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    const scannedValue = barcodeInput.value.trim();

                    if (scannedValue !== '') {
                        const found = findItemByCode(scannedValue);

                        if (found) {
                            addItemToList();
                        }
                    }
                }
            });

            addItemBtn.addEventListener('click', addItemToList);

            stockInForm.addEventListener('submit', function (event) {
                if (Object.keys(selectedItems).length === 0) {
                    event.preventDefault();
                    alert('Minimal tambahkan satu barang ke daftar transaksi.');
                    barcodeInput.focus();
                }
            });

            updateSelectedItemInfo();
        </script>
@endsection