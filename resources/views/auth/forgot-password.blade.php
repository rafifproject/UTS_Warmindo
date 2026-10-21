<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password — Sistem Informasi Warmindo</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
</head>
<body style="background-color: #FAF7F2; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
  <div style="background-color: #FFFFFF; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); border: 1px solid var(--border-card); width: 100%; max-width: 440px; padding: 40px; text-align: center;">
    <div style="margin-bottom: 24px;">
      <img src="{{ asset('images/logo.svg') }}" alt="Warmindo Logo" style="height: 48px; margin-bottom: 16px;">
      <h1 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Lupa Password?</h1>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.5;">Masukkan email yang terdaftar untuk menerima petunjuk reset password.</p>
    </div>

    @if(session('status'))
      <div style="background-color: #E6FCF5; color: #0CA678; padding: 12px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        {{ session('status') }}
      </div>
    @endif

    <form onsubmit="event.preventDefault(); alert('Tautan reset password simulasi telah dikirim ke email atau hubungi administrator outlet.');">
      <div class="form-group" style="text-align: left;">
        <label class="form-label" for="email">Email Terdaftar</label>
        <input type="email" id="email" class="form-control" placeholder="admin@warmindo.id" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 16px;">
        Kirim Link
      </button>

      <a href="{{ route('login') }}" class="btn btn-secondary btn-block">
        Kembali ke Halaman Login
      </a>
    </form>
  </div>
</body>
</html>
