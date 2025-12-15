<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - 403</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }
        h1 { font-size: 6rem; margin: 0; color: #ef4444; line-height: 1; }
        h2 { font-size: 1.5rem; margin-top: 10px; color: #1f2937; }
        p { color: #6b7280; margin-bottom: 30px; }
        .btn {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn:hover { background-color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        
        <p>{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>

        @auth
            @php
                $role = auth()->user()->ID_Role;
                $route = match($role) {
                    3 => route('trends.index'), // Kasir
                    default => route('transactions.index'), // Manajer/Pemilik
                };
            @endphp
            <a href="{{ $route }}" class="btn">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn">Login Sekarang</a>
        @endauth
    </div>
</body>
</html>