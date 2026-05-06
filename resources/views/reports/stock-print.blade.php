<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Stok</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 24px;
            background: #f4f6f9;
        }

        .report {
            background: white;
            padding: 28px;
            max-width: 1100px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }

        .report-header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #111;
            padding-bottom: 14px;
        }

        .report-header h1 {
            margin: 0 0 8px;
            font-size: 22px;
            text-transform: uppercase;
        }

        .report-header p {
            margin: 4px 0;
            font-size: 13px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        .summary-box span {
            display: block;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .summary-box strong {
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #e5e7eb;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 32px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            text-align: center;
            width: 220px;
            font-size: 13px;
        }

        .signature-space {
            height: 70px;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            border: none;
            background: #111827;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            margin: 4px;
        }

        .btn-secondary {
            background: #6b7280;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .report {
                border: none;
                max-width: 100%;
                padding: 0;
            }

            .actions {
                display: none;
            }

            @page {
                size: A4;
                margin: 16mm;
            }
        }
    </style>
</head>
<body>
    <div class="report">
        <div class="report-header">
            <h1>Laporan Stok Barang Gudang</h1>
            <p>Sistem Pencatatan Barang Masuk dan Keluar Gudang</p>
            <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }} WIB</p>
        </div>

        <div class="summary">
            <div class="summary-box">
                <span>Total Barang</span>
                <strong>{{ $totalItems }}</strong>
            </div>

            <div class="summary-box">
                <span>Total Stok</span>
                <strong>{{ $totalStock }}</strong>
            </div>

            <div class="summary-box">
                <span>Stok Menipis</span>
                <strong>{{ $lowStock }}</strong>
            </div>

            <div class="summary-box">
                <span>Stok Habis</span>
                <strong>{{ $emptyStock }}</strong>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->barcode ?? '-' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->stock }} {{ $item->unit }}</td>
                        <td>{{ $item->minimum_stock }} {{ $item->unit }}</td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td class="status">
                            @if ($item->stock == 0)
                                Habis
                            @elseif ($item->stock <= $item->minimum_stock)
                                Stok Menipis
                            @else
                                Aman
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">
                            Data stok tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature">
                <p>Petugas Gudang</p>
                <div class="signature-space"></div>
                <p><strong>{{ auth()->user()->name ?? 'Admin Gudang' }}</strong></p>
            </div>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn">
            Cetak / Simpan PDF
        </button>

        <a href="{{ route('reports.stock') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</body>
</html>