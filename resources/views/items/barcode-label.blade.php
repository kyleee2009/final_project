<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Barcode - {{ $item->name }}</title>

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

        .label-wrapper {
            width: 58mm;
            margin: 0 auto;
            background: #fff;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .label-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .item-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .item-code {
            font-size: 10px;
            margin-bottom: 6px;
        }

        .barcode-img {
            width: 100%;
            max-width: 48mm;
            height: auto;
            margin: 4px auto;
            display: block;
        }

        .barcode-text {
            font-size: 10px;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .item-info {
            font-size: 9px;
            margin-top: 6px;
            line-height: 1.4;
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

        @page {
            size: 58mm auto;
            margin: 0;
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            .label-wrapper {
                width: 58mm;
                border: none;
                margin: 0;
                padding: 6px;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="label-wrapper">
        <div class="label-title">
            Sistem Gudang
        </div>

        <div class="item-name">
            {{ $item->name }}
        </div>

        <div class="item-code">
            Kode: {{ $item->item_code }}
        </div>

        <img 
            src="data:image/png;base64,{{ $barcodeImage }}" 
            alt="Barcode {{ $barcodeValue }}"
            class="barcode-img"
        >

        <div class="barcode-text">
            {{ $barcodeValue }}
        </div>

        <div class="item-info">
            Kategori: {{ $item->category->name ?? '-' }} <br>
            Lokasi: {{ $item->location ?? '-' }}
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn">
            Cetak Label
        </button>

        <a href="{{ route('items.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</body>
</html>