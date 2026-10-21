<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | Warmindo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
    <style>
        body {
            background-color: #FAF7F2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .error-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 3.5rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            border: 1px solid #EAE5DE;
        }
        .error-icon-wrapper {
            width: 130px;
            height: 130px;
            margin: 0 auto 1.75rem auto;
            border-radius: 50%;
            background: #FFF5EE;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(240,101,36,0.1);
        }
        .error-icon-wrapper img {
            width: 80px;
            height: 80px;
        }
        .error-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.5rem 0;
        }
        .error-desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 2rem 0;
        }
        .error-btn {
            background: #F06524;
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(240,101,36,0.3);
            border: none;
            cursor: pointer;
        }
        .error-btn:hover {
            background: #d85417;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(240,101,36,0.4);
        }
    </style>
</head>
<body>
    {{-- Screen 66: Akses Ditolak / Halaman Tidak Berizin --}}
    <div class="error-card">
        <div class="error-icon-wrapper">
            <img src="{{ asset('images/access-denied.svg') }}" alt="Akses Ditolak">
        </div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-desc">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="error-btn">Kembali ke Dashboard</a>
                @else
                    <a href="{{ route('kasir.dashboard') }}" class="error-btn">Kembali ke Dashboard</a>
                @endif
            @else
                <a href="{{ route('home') }}" class="error-btn">Kembali ke Beranda</a>
            @endauth
        </div>
    </div>
</body>
</html>
