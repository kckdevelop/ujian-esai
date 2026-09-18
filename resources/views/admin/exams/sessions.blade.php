@extends('layouts.app')

@section('title', 'Detail Peserta — ' . $exam->title)

@push('styles')
<style>
    .admin-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem 0 1.75rem;
        color: #fff;
    }
    .admin-badge {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 50px;
        padding: 4px 14px;
        font-size: .78rem;
        display: inline-block;
        margin-bottom: .5rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,.03);
    }
    .stat-card .num { font-size: 2rem; font-weight: 800; }
    .stat-card .lbl { font-size: .75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-top: .25rem; }
    .table-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,.04);
    }
    .table thead th {
        background: #f8fafc;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding: .85rem 1rem;
    }
    .table tbody td {
        vertical-align: middle;
        padding: .85rem 1rem;
        border-color: #f1f5f9;
    }
    .status-badge-active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-badge-active::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        background: #15803d;
        border-radius: 50%;
        animation: blink 1.2s infinite;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: .3; }
    }
    .status-badge-finished {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="admin-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-light btn-sm" style="border-radius:8px;">
                <i class="bi bi-arrow-left me-1"></i>Daftar Ujian
            </a>
            <div class="admin-badge mb-0">
                <i class="bi bi-people-fill me-1"></i>Monitoring Peserta
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
            <div>
                <h1 class="h2 fw-bold mb-1">{{ $exam->title }}</h1>
                <div class="opacity-75 small d-flex gap-3 flex-wrap align-items-center">
                    @if($exam->grade_level)
                        <span class="badge" style="background:#ede9fe;color:#4f46e5;font-weight:700;">
                            ?? {{ $exam->grade_level }}
                        </span>
                    @endif
                    <span><i class="bi bi-key-fill me-1"></i>Token: <strong>{{ $exam->token }}</strong></span>
                    <span><i class="bi bi-clock me-1"></i>Durasi: <strong>{{ $exam->duration_minutes }} menit</strong></span>
                    <span><i class="bi bi-images me-1"></i>Jumlah Soal: <strong>{{ $exam->images()->count() }}</strong></span>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-light btn-sm fw-bold px-3" onclick="location.reload()" style="border-radius:8px;">
                    <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4 pb-5">

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num text-primary">{{ $totalParticipants }}</div>
            <div class="lbl">Total Peserta Masuk</div>
        </div>
        <div class="stat-card">
            <div class="num text-success">{{ $inProgressCount }}</div>
            <div class="lbl">Sedang Mengerjakan</div>
        </div>
        <div class="stat-card">
            <div class="num text-secondary">{{ $finishedCount }}</div>
            <div class="lbl">Sudah Selesai</div>
        </div>
        <div class="stat-card">
            <div class="num text-info">{{ $availableRooms->count() }}</div>
            <div class="lbl">Ruang Aktif</div>
        </div>
    </div>

    {{-- Filter Ruang --}}
    @if($availableRooms->isNotEmpty())
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <span class="text-muted small fw-bold"><i class="bi bi-door-open me-1"></i>Filter Ruang:</span>
        <a href="{{ route('admin.exams.sessions', $exam) }}"
           class="btn btn-sm {{ !$selectedRoom || $selectedRoom === 'all' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}"
           style="border-radius:20px;padding:3px 12px;">
            Semua Ruang ({{ $totalParticipants }})
        </a>
        @foreach($availableRooms as $room)
        <a href="{{ route('admin.exams.sessions', [$exam, 'room' => $room]) }}"
           class="btn btn-sm {{ $selectedRoom === $room ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}"
           style="border-radius:20px;padding:3px 12px;">
            {{ $room }} ({{ $exam->sessions()->where('room', $room)->count() }})
        </a>
        @endforeach
    </div>
    @endif

    {{-- Table of Sessions --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Ruang Ujian</th>
                        <th>Waktu Masuk Token</th>
                        <th>Status Ujian</th>
                        <th>Waktu Selesai</th>
                        <th>Keterangan</th>
                        <th style="width: 90px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            @if($session->room)
                                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;border:1px solid #bae6fd;font-size:.8rem;">
                                    <i class="bi bi-door-open-fill me-1"></i>{{ $session->room }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">Belum dipilih</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $session->started_at->format('d/m/Y') }}</div>
                            <div class="text-muted small">{{ $session->started_at->format('H:i:s') }} WIB</div>
                        </td>
                        <td>
                            @if(!$session->is_finished)
                                <span class="status-badge-active">
                                    Sedang Mengerjakan
                                </span>
                            @else
                                <span class="status-badge-finished">
                                    <i class="bi bi-check2-circle text-success"></i> Selesai
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($session->finished_at)
                                <div class="fw-semibold">{{ $session->finished_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $session->finished_at->format('H:i:s') }} WIB</div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($session->is_finished)
                                @if($session->finish_reason === 'manual')
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-hand-index-thumb me-1 text-primary"></i>Selesai Mandiri
                                    </span>
                                @elseif($session->finish_reason === 'auto')
                                    <span class="badge bg-light text-danger border">
                                        <i class="bi bi-alarm me-1"></i>Waktu Habis
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">{{ $session->finish_reason }}</span>
                                @endif
                            @else
                                <span class="text-muted small">Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST"
                                  onsubmit="return confirm('Hapus/reset sesi peserta ini? Siswa di perangkat ini dapat memasukkan token kembali.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;padding:3px 8px;" title="Reset / Hapus Sesi">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div style="font-size:3rem;">??</div>
                            <div class="mt-2 fw-semibold">Belum Ada Peserta yang Memasukkan Token</div>
                            <div class="small text-muted">Daftar siswa/peserta yang telah memasukkan token ujian ini akan muncul otomatis di sini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($sessions->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $sessions->links() }}
    </div>
    @endif

</div>

@endsection
