<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Gudang</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="sidebar-logo">
                Sistem<span>Gudang</span>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    Kategori Barang
                </a>

                <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                    Data Barang
                </a>

                <a href="{{ route('stock.in') }}" class="{{ request()->routeIs('stock.in') ? 'active' : '' }}">
                    Barang Masuk
                </a>

                <a href="{{ route('stock.out') }}" class="{{ request()->routeIs('stock.out') ? 'active' : '' }}">
                    Barang Keluar
                </a>

                <a href="{{ route('stock.history') }}" class="{{ request()->routeIs('stock.history') ? 'active' : '' }}">
                    Riwayat Transaksi
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <h1>@yield('title', 'Dashboard')</h1>
                <div class="topbar-user">
                    Admin Gudang
                </div>
            </header>

            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>