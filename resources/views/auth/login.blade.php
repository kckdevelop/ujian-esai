@extends('layouts.app')

@section('title', 'Login Administrator')

@push('styles')
<style>
    .login-container {
        min-height: calc(100vh - 220px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1rem;
    }
    .login-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(79, 70, 229, 0.12);
        max-width: 440px;
        width: 100%;
        overflow: hidden;
    }
    .login-header {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #fff;
        padding: 2.25rem 2rem 2rem;
        text-align: center;
        position: relative;
    }
    .login-icon-wrap {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: .85rem;
    }
    .login-body {
        padding: 2rem;
    }
    .form-control {
        border-radius: 10px;
        border-color: #cbd5e1;
        padding: .75rem 1rem;
        font-size: .95rem;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }
    .btn-login {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 12px;
        border-radius: 10px;
        font-size: 1rem;
        width: 100%;
        transition: transform .15s, box-shadow .15s;
    }
    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
        color: #fff;
    }
    .demo-creds {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: .85rem 1rem;
        margin-top: 1.5rem;
        font-size: .8rem;
        color: #64748b;
    }
    .demo-creds strong { color: #1e293b; }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-card">
        
        <div class="login-header">
            <div class="login-icon-wrap">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2 class="h4 fw-bold mb-1">Login Administrator</h2>
            <p class="mb-0 opacity-75 small">Masuk untuk mengelola paket soal dan ujian</p>
        </div>

        <div class="login-body">
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                {{-- Email / Username --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small text-secondary">
                        <i class="bi bi-person-fill text-primary me-1"></i>Email atau Username
                    </label>
                    <input
                        type="text"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email', 'admin@ujian.test') }}"
                        placeholder="admin@ujian.test"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small text-secondary">
                        <i class="bi bi-key-fill text-primary me-1"></i>Password
                    </label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-color:#cbd5e1;border-radius: 0 10px 10px 0;">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Masuk ke Panel Admin
                </button>
            </form>

            {{-- Demo credentials helper --}}
            <div class="demo-creds">
                <div class="fw-bold mb-1 text-dark">
                    <i class="bi bi-info-circle text-primary me-1"></i>Akun Default Admin:
                </div>
                <div>Email: <strong>admin@ujian.test</strong></div>
                <div>Password: <strong>admin123</strong></div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('student.index') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard Siswa
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle show/hide password
const toggleBtn = document.getElementById('togglePassword');
const passInput = document.getElementById('password');
const eyeIcon   = document.getElementById('eyeIcon');

if (toggleBtn && passInput) {
    toggleBtn.addEventListener('click', function () {
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
}
</script>
@endpush
