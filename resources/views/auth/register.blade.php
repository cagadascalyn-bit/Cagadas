<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MusicApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg: #1a0010; --bg2: #2d0020; --border: #4d1a35; --text: #e0e0e0; --muted: #6b7280; --accent: #f472b6; --btn: #db2777; --btn-hover: #be185d; --input: #1a0010; }
        [data-theme="light"] { --bg: #fff0f6; --bg2: #ffffff; --border: #f9a8d4; --text: #3b0a2a; --muted: #9d174d; --accent: #db2777; --btn: #db2777; --btn-hover: #be185d; --input: #fff0f6; }
        * { transition: background .2s, color .2s, border-color .2s; }
        body { background: var(--bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; color: var(--text); }
        .form-control { background: var(--input); border-color: var(--border); color: var(--text); }
        .form-control:focus { background: var(--input); border-color: var(--accent); color: var(--text); box-shadow: 0 0 0 .2rem #f472b633; }
        .btn-primary { background: var(--btn); border-color: var(--btn); color: #fff; }
        .btn-primary:hover { background: var(--btn-hover); border-color: var(--btn-hover); }
        label { color: var(--muted); }
        .toast-container { z-index: 9999; }
        .theme-toggle { position: fixed; top: 16px; right: 16px; background: var(--bg2); border: 1px solid var(--border); color: var(--accent); border-radius: 20px; padding: 5px 14px; cursor: pointer; font-size: .85rem; }
    </style>
</head>
<body>
<button class="theme-toggle" onclick="toggleTheme()"><i class="bi bi-sun-fill" id="themeIcon"></i> <span id="themeLabel">Light</span></button>
<div class="auth-card">
    <div class="text-center mb-4">
        <h2 style="color:var(--accent);"><i class="bi bi-music-note-beamed"></i> MusicApp</h2>
        <p style="color:var(--muted);">Create your account</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-4">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="text-center mt-3" style="color:var(--muted);">Already have an account? <a href="{{ route('login') }}" style="color:var(--accent);">Login</a></p>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('toast_success'))
    <div class="toast align-items-center text-white border-0 show" style="background:#db2777;">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle me-2"></i>{{ session('toast_success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 4000 }).show());
    const html = document.documentElement;
    const icon = document.getElementById('themeIcon');
    const label = document.getElementById('themeLabel');
    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        localStorage.setItem('theme', t);
        icon.className = t === 'light' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
        label.textContent = t === 'light' ? 'Dark' : 'Light';
    }
    function toggleTheme() { applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); }
    applyTheme(localStorage.getItem('theme') || 'dark');
</script>
</body>
</html>
