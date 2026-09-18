@extends('layouts.app')

@section('title', 'Ujian Selesai')

@section('content')
<div class="container py-5" style="max-width:600px;">
    <div class="text-center py-5">
        <div style="font-size:6rem;margin-bottom:1rem;">🎉</div>

        <h1 class="fw-800 mb-2" style="font-weight:800;font-size:2.2rem;">Ujian Selesai!</h1>
        <p class="text-muted mb-4 fs-5">
            Terima kasih telah mengikuti ujian.<br>
            Jawaban kamu telah tercatat oleh sistem.
        </p>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">
            <div class="card-body p-4" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);">
                <h5 class="fw-bold text-primary mb-1">
                    <i class="bi bi-journal-text me-2"></i>{{ $exam->title }}
                </h5>
                <p class="text-muted small mb-0">
                    Selesai pada: <strong>{{ now()->format('d M Y H:i') }}</strong>
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 text-start" style="border-radius:16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-muted text-uppercase" style="letter-spacing:.5px;font-size:.75rem;">
                    <i class="bi bi-info-circle me-1"></i>Informasi
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success">Terkirim</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Paket Soal</span>
                        <strong>{{ $exam->title }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Durasi Ujian</span>
                        <strong>{{ $exam->duration_minutes }} menit</strong>
                    </li>
                </ul>
            </div>
        </div>

        <a href="{{ route('student.index') }}" class="btn btn-primary btn-lg px-5">
            <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
