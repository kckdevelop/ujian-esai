@extends('layouts.app')

@section('title', 'Admin — Daftar Paket Ujian')

@push('styles')
<style>
    .admin-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2.5rem 0 2rem;
        color: #fff;
    }
    .admin-badge {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 50px;
        padding: 4px 14px;
        font-size: .78rem;
        display: inline-block;
        margin-bottom: .75rem;
    }
    .table-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
    }
    .table thead th {
        background: #f8fafc;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding: .9rem 1rem;
    }
    .table tbody td {
        vertical-align: middle;
        padding: .85rem 1rem;
        border-color: #f1f5f9;
    }
    .table tbody tr:hover { background: #f8faff; }
    .token-badge {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 2px;
        background: #ede9fe;
        color: #4f46e5;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: .85rem;
    }
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-active   { background: #10b981; }
    .status-inactive { background: #ef4444; }
    .btn-action {
        padding: 5px 12px;
        font-size: .78rem;
        font-weight: 600;
        border-radius: 7px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
    }
    .stat-card .num { font-size: 2rem; font-weight: 800; color: #4f46e5; }
    .stat-card .lbl { font-size: .75rem; color: #94a3b8; text-transform: uppercase; }
</style>
@endpush

@section('content')

<div class="admin-header">
    <div class="container">
        <div class="admin-badge">
            <i class="bi bi-shield-lock me-1"></i>Panel Admin
        </div>
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h1 class="h2 fw-800 mb-1" style="font-weight:800;">Manajemen Paket Ujian</h1>
                <p class="mb-0 opacity-75">Kelola semua paket soal, jadwal, dan token ujian</p>
            </div>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-light fw-bold px-4" style="border-radius:10px;">
                <i class="bi bi-plus-lg me-1"></i>Tambah Ujian
            </a>
        </div>
    </div>
</div>

<div class="container mt-4">

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num">{{ $exams->total() }}</div>
            <div class="lbl">Total Ujian</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#10b981;">
                {{ $exams->filter(fn($e) => $e->is_active)->count() }}
            </div>
            <div class="lbl">Aktif</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#06b6d4;">
                {{ $exams->sum('images_count') }}
            </div>
            <div class="lbl">Total Gambar</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#f59e0b;">
                {{ $exams->sum('sessions_count') }}
            </div>
            <div class="lbl">Sesi Dikerjakan</div>
        </div>
    </div>

    {{-- Filter Tingkat Kelas --}}
    @if($allGradeLevels->isNotEmpty())
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <span class="text-muted small fw-bold"><i class="bi bi-funnel me-1"></i>Filter Tingkat:</span>
        <a href="{{ route('admin.exams.index') }}"
           class="btn btn-sm {{ !$selectedGrade || $selectedGrade === 'all' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}"
           style="border-radius:20px;padding:3px 12px;">
            Semua ({{ $exams->total() }})
        </a>
        @foreach($allGradeLevels as $g)
        <a href="{{ route('admin.exams.index', ['grade_level' => $g]) }}"
           class="btn btn-sm {{ $selectedGrade === $g ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}"
           style="border-radius:20px;padding:3px 12px;">
            🎓 {{ $g }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Table --}}
    <div class="table-card bg-white mb-5">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Ujian</th>
                        <th>Tingkat</th>
                        <th>Token</th>
                        <th>Jadwal</th>
                        <th>Durasi</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $exam->title }}</div>
                            @if($exam->description)
                                <div class="text-muted small">{{ Str::limit($exam->description, 40) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($exam->grade_level)
                                <span class="badge" style="background:#ede9fe;color:#4f46e5;font-weight:700;border:1px solid #c7d2fe;">
                                    🎓 {{ $exam->grade_level }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">Umum</span>
                            @endif
                        </td>
                        <td>
                            <span class="token-badge" title="Klik untuk menyalin" onclick="copyToken('{{ $exam->token }}', this)" style="cursor:pointer;">
                                {{ $exam->token }}
                            </span>
                        </td>
                        <td class="small">
                            <div><i class="bi bi-play text-success me-1"></i>{{ $exam->start_time->format('d/m/Y H:i') }}</div>
                            <div><i class="bi bi-stop text-danger me-1"></i>{{ $exam->end_time->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $exam->duration_minutes }}</span>
                            <span class="text-muted small">menit</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-images me-1"></i>{{ $exam->images_count }}
                            </span>
                        </td>
                        <td>
                            @if($exam->is_active)
                                <span>
                                    <span class="status-dot status-active"></span>Aktif
                                </span>
                            @else
                                <span class="text-muted">
                                    <span class="status-dot status-inactive"></span>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('admin.exams.sessions', $exam) }}"
                                   class="btn btn-outline-primary btn-action text-nowrap d-inline-flex align-items-center gap-1"
                                   title="Lihat Peserta yang telah memasukkan token">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Peserta ({{ $exam->sessions_count }})</span>
                                </a>
                                <a href="{{ route('admin.exams.preview', $exam) }}" target="_blank"
                                   class="btn btn-outline-info btn-action" title="Pratinjau Lembar Soal Siswa">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.exams.edit', $exam) }}"
                                   class="btn btn-outline-secondary btn-action" title="Edit & Kelola Soal">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST"
                                      onsubmit="return confirm('Hapus paket ujian ini beserta semua gambarnya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-action" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div style="font-size:3rem;">📭</div>
                            <div class="mt-2">Belum ada paket ujian.</div>
                            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-sm mt-3">
                                <i class="bi bi-plus-lg me-1"></i>Buat Ujian Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center pb-4">
        {{ $exams->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyToken(token, el) {
    navigator.clipboard.writeText(token).then(() => {
        const orig = el.textContent;
        el.textContent = '✓ Disalin!';
        el.style.background = '#dcfce7';
        el.style.color = '#15803d';
        setTimeout(() => {
            el.textContent = orig;
            el.style.background = '';
            el.style.color = '';
        }, 2000);
    });
}
</script>
@endpush
