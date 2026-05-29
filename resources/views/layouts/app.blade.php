<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎵 MusicApp - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ── Dark Mode (default) ── */
        [data-theme="dark"] {
            --bg:           #1a0010;
            --bg2:          #2d0020;
            --border:       #4d1a35;
            --text:         #e0e0e0;
            --text-muted:   #9ca3af;
            --accent:       #f472b6;
            --accent-btn:   #db2777;
            --accent-hover: #be185d;
            --accent-soft:  #db277722;
            --input-bg:     #1a0010;
            --topbar-bg:    #2d0020;
            --sidebar-grad: linear-gradient(180deg, #2d0020 0%, #1a0010 100%);
            --table-head:   #1a0010;
        }
        /* ── Light Mode ── */
        [data-theme="light"] {
            --bg:           #fff0f6;
            --bg2:          #ffffff;
            --border:       #f9a8d4;
            --text:         #3b0a2a;
            --text-muted:   #9d174d;
            --accent:       #db2777;
            --accent-btn:   #db2777;
            --accent-hover: #be185d;
            --accent-soft:  #fce7f3;
            --input-bg:     #fff0f6;
            --topbar-bg:    #fce7f3;
            --sidebar-grad: linear-gradient(180deg, #fce7f3 0%, #fff0f6 100%);
            --table-head:   #fce7f3;
        }

        * { transition: background .2s, color .2s, border-color .2s; }

        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; }

        .sidebar { width: 240px; min-height: 100vh; background: var(--sidebar-grad); position: fixed; top: 0; left: 0; z-index: 100; padding-top: 20px; border-right: 1px solid var(--border); }
        .sidebar .brand { color: var(--accent); font-size: 1.4rem; font-weight: 700; padding: 10px 20px 20px; border-bottom: 1px solid var(--border); }
        .sidebar .nav-link { color: var(--text-muted); padding: 10px 20px; border-radius: 8px; margin: 2px 10px; transition: all .2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: var(--accent-soft); color: var(--accent); }
        .sidebar .nav-link i { margin-right: 8px; }

        .main-content { margin-left: 240px; padding: 20px; }
        .topbar { background: var(--topbar-bg); border-bottom: 1px solid var(--border); padding: 12px 20px; margin: -20px -20px 20px; display: flex; justify-content: space-between; align-items: center; }

        .card { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; }
        .card-header { background: var(--table-head); border-bottom: 1px solid var(--border); color: var(--text); }

        .table { color: var(--text); }
        .table thead th { background: var(--table-head); color: var(--accent); border-color: var(--border); }
        .table td, .table th { border-color: var(--border); vertical-align: middle; }
        .table-hover tbody tr:hover { background: var(--accent-soft); }

        .btn-primary { background: var(--accent-btn); border-color: var(--accent-btn); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); border-color: var(--accent-hover); color: #fff; }

        .form-control, .form-select { background: var(--input-bg); border-color: var(--border); color: var(--text); }
        .form-control:focus, .form-select:focus { background: var(--input-bg); border-color: var(--accent); color: var(--text); box-shadow: 0 0 0 .2rem #f472b633; }
        .form-control::placeholder { color: var(--text-muted); opacity: .7; }

        .modal-content { background: var(--bg2); border: 1px solid var(--border); color: var(--text); }
        .modal-header { border-bottom: 1px solid var(--border); }
        .modal-footer { border-top: 1px solid var(--border); }

        .stat-card { border-radius: 12px; padding: 20px; }
        .toast-container { z-index: 9999; }
        .badge-genre { background: var(--accent-soft); color: var(--accent); border: 1px solid var(--border); }

        /* Theme Toggle Button */
        .theme-toggle { background: var(--accent-soft); border: 1px solid var(--border); color: var(--accent); border-radius: 20px; padding: 4px 12px; cursor: pointer; font-size: .85rem; display: flex; align-items: center; gap: 6px; }
        .theme-toggle:hover { background: var(--accent-btn); color: #fff; }

        /* Light mode sidebar text fix */
        [data-theme="light"] .sidebar .nav-link { color: #9d174d; }
        [data-theme="light"] .sidebar .nav-link:hover,
        [data-theme="light"] .sidebar .nav-link.active { color: #db2777; background: #fce7f3; }
        [data-theme="light"] .topbar h5 { color: #db2777; }
        [data-theme="light"] span.user-name { color: #9d174d; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand"><i class="bi bi-music-note-beamed"></i> MusicApp</div>
    <nav class="nav flex-column mt-3">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('songs.index') }}" class="nav-link {{ request()->routeIs('songs.*') ? 'active' : '' }}">
            <i class="bi bi-music-note-list"></i> My Playlist
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profile
        </a>
        <hr style="border-color:var(--border); margin: 10px 20px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-start w-100" style="color:#ef4444;">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </nav>
</div>

{{-- Main Content --}}
<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0" style="color:var(--accent);">@yield('title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-3">
            {{-- Theme Toggle --}}
            <button class="theme-toggle" onclick="toggleTheme()" id="themeBtn">
                <i class="bi bi-sun-fill" id="themeIcon"></i>
                <span id="themeLabel">Light</span>
            </button>

            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle" width="32" height="32" style="object-fit:cover;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#db2777;font-size:.8rem;color:#fff;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <span class="user-name" style="color:var(--text-muted);">{{ Auth::user()->name }}</span>
        </div>
    </div>

    @yield('content')
</div>

{{-- Toast Notifications --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('toast_success'))
    <div class="toast align-items-center text-white border-0 show" role="alert" style="background:#db2777;" id="liveToast">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle me-2"></i>{{ session('toast_success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('toast_error'))
    <div class="toast align-items-center text-white border-0 show" role="alert" style="background:#dc2626;" id="liveToastErr">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-x-circle me-2"></i>{{ session('toast_error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Auto-hide toasts
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 4000 }).show());

    // Theme toggle
    const html  = document.documentElement;
    const icon  = document.getElementById('themeIcon');
    const label = document.getElementById('themeLabel');

    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (theme === 'light') {
            icon.className  = 'bi bi-moon-fill';
            label.textContent = 'Dark';
        } else {
            icon.className  = 'bi bi-sun-fill';
            label.textContent = 'Light';
        }
    }

    function toggleTheme() {
        applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    }

    // Load saved theme on page load
    applyTheme(localStorage.getItem('theme') || 'dark');
</script>
@stack('scripts')
</body>
</html>
