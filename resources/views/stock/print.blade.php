{{-- print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk - {{ $transaction->transaction_code }}</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 20px;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #111;
        }

        .receipt {
            width: 72mm;
            margin: 0 auto;
            background: #fff;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .receipt-header h2 {
            font-size: 16px;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        .receipt-header p {
            font-size: 11px;
            margin: 0;
        }

        .receipt-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .receipt-row .label {
            width: 38%;
            font-weight: bold;
        }

        .receipt-row .value {
            width: 62%;
            text-align: right;
            word-break: break-word;
        }

        /* ===== TABEL DETAIL BARANG (BARU) ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 6px 0;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            padding: 3px 2px;
            text-align: left;
            font-size: 10px;
        }

        .items-table td {
            padding: 4px 2px;
            vertical-align: top;
            font-size: 10px;
        }

        .items-table tr:not(:last-child) td {
            border-bottom: 1px dotted #ccc;
        }

        .items-table .qty-col {
            text-align: right;
            white-space: nowrap;
        }

        .items-table .stock-col {
            text-align: right;
            white-space: nowrap;
        }

        .section-label {
            font-size: 10px;
            font-weight: bold;
            margin: 8px 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            font-weight: bold;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #000;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .transaction-code {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0;
            padding: 8px;
            border: 1px dashed #000;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 12px;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            background: #111827;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin: 4px;
        }

        .btn-secondary { background: #6b7280; }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .receipt {
                width: 72mm;
                margin: 0;
                border: none;
                padding: 8px;
            }

            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">

        {{-- HEADER --}}
        <div class="receipt-header">
            <h2>Sistem Gudang</h2>
            <p>Bukti Transaksi Barang</p>
        </div>

        {{-- JUDUL JENIS TRANSAKSI --}}
        <div class="receipt-title">
            {{ $transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}
        </div>

        {{-- KODE TRANSAKSI --}}
        <div class="transaction-code">
            {{ $transaction->transaction_code }}
        </div>

        {{-- INFO TRANSAKSI --}}
        <div class="receipt-row">
            <div class="label">Tanggal</div>
            <div class="value">{{ $transaction->transaction_date->format('d-m-Y H:i') }} WIB</div>
        </div>

        <div class="receipt-row">
            <div class="label">Jenis</div>
            <div class="value">
                {{ $transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}
            </div>
        </div>

        <div class="receipt-row">
            <div class="label">{{ $transaction->type == 'in' ? 'Sumber' : 'Tujuan' }}</div>
            <div class="value">{{ $transaction->source_or_destination ?? '-' }}</div>
        </div>

        <div class="receipt-row">
            <div class="label">Petugas</div>
            <div class="value">{{ $transaction->user->name ?? 'Admin Gudang' }}</div>
        </div>

        <div class="divider"></div>

        {{-- ===== DETAIL BARANG (LOOP) ===== --}}
        <div class="section-label">Detail Barang</div>

        @php $totalQty = 0; @endphp

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%">Barang</th>
                    <th class="qty-col" style="width: 15%">Qty</th>
                    <th class="stock-col" style="width: 20%">Sblm</th>
                    <th class="stock-col" style="width: 25%">Ssdh</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaction->details as $detail)
                    @php $totalQty += $detail->quantity; @endphp
                    <tr>
                        <td>
                            <strong>{{ $detail->item->name ?? '-' }}</strong><br>
                            <span style="color:#555">{{ $detail->item->item_code ?? '' }}</span>
                        </td>
                        <td class="qty-col">
                            {{ $detail->quantity }}<br>
                            <span style="color:#555">{{ $detail->item->unit ?? '' }}</span>
                        </td>
                        <td class="stock-col">{{ $detail->stock_before }}</td>
                        <td class="stock-col">{{ $detail->stock_after }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#999;">
                            Tidak ada data barang
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- TOTAL --}}
        <div class="summary-row">
            <span>Total Item: {{ $transaction->details->count() }}</span>
            <span>Total Qty: {{ $totalQty }}</span>
        </div>

        {{-- KETERANGAN (jika ada) --}}
        @if ($transaction->description)
            <div class="divider"></div>
            <div class="receipt-row">
                <div class="label">Ket.</div>
                <div class="value">{{ $transaction->description }}</div>
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <p>Struk ini dicetak otomatis oleh Sistem Gudang.</p>
            <p>Terima kasih.</p>
        </div>

    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn">Cetak Struk</button>
        <a href="{{ route('stock.history') }}" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>