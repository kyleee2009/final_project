<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Gudang</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Sistem Gudang</h1>
                <p>Login admin untuk mengelola pencatatan barang masuk dan keluar.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Login gagal.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email admin"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary login-btn">
                    Login
                </button>
            </form>

            <div class="login-footer">
                <p>Project Tugas Akhir Sistem Pencatatan Gudang</p>
            </div>
        </div>
    </div>
</body>
</html>