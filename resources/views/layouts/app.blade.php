<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ujian Online') — Ujian Online</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #4f46e5;
            --primary-dark: #3730a3;
            --accent:       #06b6d4;
            --success:      #10b981;
            --danger:       #ef4444;
            --bg:           #f1f5f9;
            --card-bg:      #ffffff;
            --text:         #1e293b;
            --muted:        #64748b;
            --border:       #e2e8f0;
            --radius:       12px;
            --shadow:       0 4px 24px rgba(79,70,229,.10);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        .navbar-brand { font-weight: 800; letter-spacing: -.5px; }
        .navbar-brand span { color: var(--accent); }
        .nav-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 12px rgba(79,70,229,.25);
        }
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            font-weight: 600;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.35);
        }
        .badge-active   { background: #dcfce7; color: #15803d; }
        .badge-inactive { background: #fee2e2; color: #dc2626; }
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: #fff;
            padding: 2.5rem 0 2rem;
            margin-bottom: 2rem;
        }
        .alert { border-radius: var(--radius); border: none; }
        footer { border-top: 1px solid var(--border); }
    </style>

    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark nav-custom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('student.index') }}">
            <i class="bi bi-mortarboard-fill me-2"></i>Ujian<span>Online</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.*') ? 'active fw-semibold' : '' }}" href="{{ route('student.index') }}">
                        <i class="bi bi-house-door me-1"></i>Dashboard Siswa
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active fw-semibold' : '' }}" href="{{ route('admin.exams.index') }}">
                            <i class="bi bi-gear-fill me-1"></i>Admin Panel
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-1 text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="badge bg-white text-primary rounded-pill px-2 py-1 me-1">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <span class="dropdown-item-text text-muted small">
                                    <i class="bi bi-envelope me-1"></i>{{ Auth::user()->email }}
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.exams.index') }}">
                                    <i class="bi bi-grid-fill text-primary me-2"></i>Kelola Ujian
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light px-3 rounded-pill fw-semibold ms-lg-2" href="{{ route('login') }}">
                            <i class="bi bi-shield-lock me-1"></i>Login Admin
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="py-4 mt-5 text-center text-muted small">
    <div class="container">
        &copy; {{ date('Y') }} Ujian Online &mdash; Sistem Ujian Berbasis Slide Gambar
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
