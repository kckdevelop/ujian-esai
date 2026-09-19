@extends('layouts.app')

@section('title', 'Ubah Password Admin')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-white-50">
                        <li class="breadcrumb-item"><a href="{{ route('admin.exams.index') }}" class="text-white-50 text-decoration-none">Admin</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Ubah Password</li>
                    </ol>
                </nav>
                <h2 class="mb-0 fw-bold">
                    <i class="bi bi-shield-lock me-2"></i>Ubah Password Admin
                </h2>
            </div>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-key-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Ganti Kata Sandi</h5>
                            <small class="text-muted">Akun: <strong>{{ $user->name }}</strong> ({{ $user->email }})</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Password Saat Ini --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       placeholder="Masukkan password saat ini" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-3 text-muted opacity-25">

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-shield-plus text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text small text-muted">
                                Disarankan kombinasi huruf, angka, dan simbol.
                            </div>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-check2-circle text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 border-end-0" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password baru" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan Password
                            </button>
                            <a href="{{ route('admin.exams.index') }}" class="btn btn-light py-2 rounded-3 text-muted">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
});
</script>
@endpush
