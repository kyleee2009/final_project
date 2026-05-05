<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk - {{ $transaction->transaction_code }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

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

        .btn-secondary {
            background: #6b7280;
        }

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

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2>Sistem Gudang</h2>
            <p>Bukti Transaksi Barang</p>
        </div>

        <div class="receipt-title">
            @if ($transaction->type == 'in')
                Barang Masuk
            @else
                Barang Keluar
            @endif
        </div>

        <div class="transaction-code">
            {{ $transaction->transaction_code }}
        </div>

        <div class="receipt-row">
            <div class="label">Tanggal</div>
            <div class="value">{{ $transaction->transaction_date->format('d-m-Y H:i') }} WIB</div>
        </div>

        <div class="receipt-row">
            <div class="label">Kode Barang</div>
            <div class="value">{{ $transaction->item->item_code ?? '-' }}</div>
        </div>

        <div class="receipt-row">
            <div class="label">Barcode</div>
            <div class="value">{{ $transaction->item->barcode ?? '-' }}</div>
        </div>

        <div class="receipt-row">
            <div class="label">Nama Barang</div>
            <div class="value">{{ $transaction->item->name ?? '-' }}</div>
        </div>

        <div class="receipt-row">
            <div class="label">Kategori</div>
            <div class="value">{{ $transaction->item->category->name ?? '-' }}</div>
        </div>

        <div class="divider"></div>

        <div class="receipt-row">
            <div class="label">Jenis</div>
            <div class="value">
                {{ $transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}
            </div>
        </div>

        <div class="receipt-row">
            <div class="label">Jumlah</div>
            <div class="value">
                {{ $transaction->quantity }} {{ $transaction->item->unit ?? '' }}
            </div>
        </div>

        <div class="receipt-row">
            <div class="label">Stok Sebelum</div>
            <div class="value">
                {{ $transaction->stock_before }} {{ $transaction->item->unit ?? '' }}
            </div>
        </div>

        <div class="receipt-row">
            <div class="label">Stok Sesudah</div>
            <div class="value">
                {{ $transaction->stock_after }} {{ $transaction->item->unit ?? '' }}
            </div>
        </div>

        <div class="receipt-row">
            <div class="label">
                {{ $transaction->type == 'in' ? 'Sumber' : 'Tujuan' }}
            </div>
            <div class="value">{{ $transaction->source_or_destination ?? '-' }}</div>
        </div>

        <div class="receipt-row">
            <div class="label">Petugas</div>
            <div class="value">{{ $transaction->user->name ?? 'Admin Gudang' }}</div>
        </div>

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
        <button onclick="window.print()" class="btn">
            Cetak Struk
        </button>

        <a href="{{ route('stock.history') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</body>
</html>