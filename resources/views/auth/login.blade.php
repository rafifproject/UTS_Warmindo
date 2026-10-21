<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistem Informasi Warmindo</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
</head>
<body style="background-color: #FAF7F2; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
  <div style="background-color: #FFFFFF; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); border: 1px solid var(--border-card); width: 100%; max-width: 880px; overflow: hidden; display: flex; flex-wrap: wrap;">
    
    <!-- Left Visual Panel -->
    <div style="flex: 1; min-width: 320px; background: linear-gradient(135deg, #164032 0%, #0F2D23 100%); padding: 48px; color: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
      <div>
        <img src="{{ asset('images/logo-white.svg') }}" alt="Warmindo Logo" style="height: 48px; margin-bottom: 24px;">
        <h2 style="font-size: 26px; font-weight: 800; color: #FFFFFF; margin-bottom: 12px; line-height: 1.25;">Sistem Informasi Operasional Warmindo</h2>
        <p style="font-size: 14px; opacity: 0.85; line-height: 1.6;">Pemesanan online pelanggan, pencocokan & pembayaran kasir, antrean dapur (KDS), laporan bisnis interaktif, dan AI Assistant Owner.</p>
      </div>

      <div style="margin-top: 36px; text-align: center;">
        <div style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; margin: 0 auto; border: 5px solid rgba(255, 255, 255, 0.15); box-shadow: 0 12px 28px rgba(0,0,0,0.3);">
          <img src="{{ asset('images/menu/indomie-goreng.png') }}" alt="Indomie Goreng" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <p style="margin-top: 14px; font-size: 13px; font-weight: 600; color: #FFA940;">Warmindo — Lebih dari Sekadar Mie</p>
      </div>
    </div>

    <!-- Right Login Form Panel -->
    <div style="flex: 1.1; min-width: 320px; padding: 48px; display: flex; flex-direction: column; justify-content: center;">
      <div style="margin-bottom: 28px;">
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">Login</h1>
        <p style="font-size: 14px; color: var(--text-muted);">Masuk ke akun internal Anda untuk mengelola Warmindo</p>
      </div>

      @if($errors->has('login'))
        <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
          {{ $errors->first('login') }}
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label" for="login">Email atau Username</label>
          <input type="text" name="login" id="login" class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" placeholder="contoh: admin@warmindo.id atau siti" value="{{ old('login') }}" required autofocus>
        </div>

        <div class="form-group">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
            <a href="{{ route('password.request') }}" style="font-size: 12.5px; color: var(--primary); font-weight: 600;">Lupa password?</a>
          </div>
          <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--text-body); cursor: pointer;">
            <input type="checkbox" name="remember" value="1">
            <span>Ingat saya di perangkat ini</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 24px;">
          Masuk ke Sistem
        </button>
      </form>

      <!-- Demo Accounts Box -->
      <div style="background-color: #F8F9FA; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 16px;">
        <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 8px;">Akun Demo Pengujian:</p>
        <div style="font-size: 12.5px; line-height: 1.6; color: var(--text-body);">
          <div style="margin-bottom: 4px;">
            <strong>Admin / Owner:</strong> <code style="background: #E9ECEF; padding: 2px 6px; border-radius: 4px;">admin@warmindo.id</code> / <code style="background: #E9ECEF; padding: 2px 6px; border-radius: 4px;">password</code>
          </div>
          <div>
            <strong>Kasir / Staff:</strong> <code style="background: #E9ECEF; padding: 2px 6px; border-radius: 4px;">kasir@warmindo.id</code> / <code style="background: #E9ECEF; padding: 2px 6px; border-radius: 4px;">password</code>
          </div>
        </div>
      </div>

      <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('home') }}" style="font-size: 13.5px; color: var(--text-muted); font-weight: 600;">
          &larr; Kembali ke Katalog Pelanggan
        </a>
      </div>
    </div>
  </div>
</body>
</html>
