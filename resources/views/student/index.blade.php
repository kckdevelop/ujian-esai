@extends('layouts.app')

@section('title', 'Dashboard Ujian Siswa')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        padding: 3.5rem 0 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-title { font-size: 2.25rem; font-weight: 800; }
    .hero-subtitle { opacity: .85; font-size: 1.05rem; }
    .hero-badge {
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 50px;
        padding: 4px 14px;
        font-size: .8rem;
        display: inline-block;
        margin-bottom: .75rem;
    }

    /* Exam Cards */
    .exam-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        padding: 1.5rem;
        transition: transform .2s, box-shadow .2s, border-color .2s;
        position: relative;
        overflow: hidden;
    }
    .exam-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
    }
    .exam-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(79,70,229,.18);
        border-color: #c7d2fe;
    }
    .exam-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: .5rem;
    }
    .exam-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1rem;
    }
    .exam-meta-item {
        display: flex;
        align-items: center;
        gap: .35rem;
        font-size: .8rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 6px;
        padding: 4px 10px;
    }
    .exam-meta-item i { color: #4f46e5; }
    .btn-start {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 10px;
        width: 100%;
        transition: transform .15s, box-shadow .15s;
        font-size: .9rem;
    }
    .btn-start:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(79,70,229,.4);
        color: #fff;
    }
    .btn-start i { margin-right: 6px; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
        display: block;
    }
    .empty-state h3 { color: #334155; font-weight: 700; }
    .empty-state p  { color: #94a3b8; }

    /* Live badge */
    .badge-live {
        background: #dcfce7;
        color: #15803d;
        font-size: .7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-live::before {
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

    /* Countdown */
    .countdown-mini {
        font-size: .78rem;
        color: #94a3b8;
    }

    /* Modal token */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 24px 80px rgba(79,70,229,.25);
    }
    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 1.75rem 1rem;
    }
    .modal-header .modal-title { font-weight: 800; }
    .modal-body { padding: 1.5rem 1.75rem; }
    .modal-footer { border-top: 1px solid #e2e8f0; padding: 1rem 1.75rem 1.5rem; }
    .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.15); }
    .token-input {
        font-family: 'Courier New', monospace;
        font-size: 1.4rem;
        font-weight: 700;
        text-align: center;
        letter-spacing: 4px;
        text-transform: uppercase;
        border-radius: 12px;
    }

    /* Stats bar */
    .stats-bar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }
    /* Grade Level Tabs */
    .grade-tab {
        border-radius: 50px;
        padding: 6px 18px;
        font-weight: 700;
        font-size: .85rem;
        background: #fff;
        color: #475569;
        border: 1px solid #cbd5e1;
        transition: all .2s;
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
    }
    .grade-tab:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #94a3b8;
    }
    .grade-tab.active {
        background: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 14px rgba(79,70,229,.35);
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="hero-section">
    <div class="container position-relative">
        <div class="hero-badge">
            <i class="bi bi-calendar-check me-1"></i>
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </div>
        <h1 class="hero-title">Dashboard Ujian Siswa</h1>
        <p class="hero-subtitle">Berikut daftar paket ujian yang tersedia untuk hari ini.</p>
    </div>
</div>

<div class="container" style="margin-top: -1rem;">

    {{-- Stats --}}
    <div class="stats-bar shadow-sm">
        <div class="row g-0 text-center">
            <div class="col-4 stat-item">
                <div class="stat-number">{{ $exams->count() }}</div>
                <div class="stat-label">Total Ujian Aktif</div>
            </div>
            <div class="col-4 stat-item" style="border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
                <div class="stat-number">{{ $gradeLevels->count() ?: 1 }}</div>
                <div class="stat-label">Tingkat Kelas</div>
            </div>
            <div class="col-4 stat-item">
                <div class="stat-number" id="liveServerClock">{{ now()->format('H:i:s') }}</div>
                <div class="stat-label"><i class="bi bi-clock me-1"></i>Waktu Server</div>
            </div>
        </div>
    </div>

    {{-- Filter Tab Tingkat Kelas --}}
    @if($gradeLevels->isNotEmpty())
    <div class="d-flex align-items-center gap-2 mb-4 flex-wrap justify-content-center">
        <button type="button" class="btn btn-sm grade-tab active" data-grade="all" onclick="filterGrade('all', this)">
            <i class="bi bi-grid-fill me-1"></i>Semua Tingkat ({{ $exams->count() }})
        </button>
        @foreach($gradeLevels as $grade)
        <button type="button" class="btn btn-sm grade-tab" data-grade="{{ Str::slug($grade) }}" onclick="filterGrade('{{ Str::slug($grade) }}', this)">
            <i class="bi bi-mortarboard-fill me-1"></i>{{ $grade }} ({{ $exams->where('grade_level', $grade)->count() }})
        </button>
        @endforeach
    </div>
    @endif

    {{-- Exam Grid per Tingkat --}}
    @if($exams->isEmpty())
        <div class="empty-state">
            <span class="empty-state-icon">📅</span>
            <h3>Tidak Ada Ujian Hari Ini</h3>
            <p>Belum ada paket ujian yang dijadwalkan untuk saat ini.<br>Silakan cek kembali nanti.</p>
        </div>
    @else
        <div id="examsContainer">
            @foreach($groupedExams as $groupGrade => $groupList)
            <div class="grade-section mb-5" data-grade-section="{{ Str::slug($groupGrade) }}">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="fs-5 fw-bold text-dark">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>{{ $groupGrade }}
                    </span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                        {{ $groupList->count() }} Paket Ujian
                    </span>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @foreach($groupList as $exam)
                    <div class="col exam-item" data-grade="{{ Str::slug($exam->grade_level ?: 'Umum / Lainnya') }}">
                        <div class="exam-card h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge" style="background:#ede9fe;color:#4f46e5;font-weight:700;border:1px solid #c7d2fe;font-size:.75rem;">
                                    <i class="bi bi-mortarboard-fill me-1"></i>{{ $exam->grade_level ?: 'Umum' }}
                                </span>
                                <span class="badge-live ms-2 flex-shrink-0">LIVE</span>
                            </div>

                            <h2 class="exam-card-title mt-1">{{ $exam->title }}</h2>

                            @if($exam->description)
                                <p class="text-muted small mb-3">{{ Str::limit($exam->description, 80) }}</p>
                            @endif

                            <div class="exam-meta">
                                <div class="exam-meta-item">
                                    <i class="bi bi-clock"></i>
                                    {{ $exam->duration_minutes }} menit
                                </div>
                                <div class="exam-meta-item">
                                    <i class="bi bi-images"></i>
                                    {{ $exam->images_count }} halaman
                                </div>
                                <div class="exam-meta-item">
                                    <i class="bi bi-play-circle"></i>
                                    {{ $exam->start_time->format('H:i') }}
                                </div>
                                <div class="exam-meta-item">
                                    <i class="bi bi-stop-circle"></i>
                                    {{ $exam->end_time->format('H:i') }}
                                </div>
                            </div>

                            <button
                                type="button"
                                class="btn btn-start"
                                data-bs-toggle="modal"
                                data-bs-target="#modalToken"
                                data-exam-id="{{ $exam->id }}"
                                data-exam-title="{{ $exam->title }}"
                                data-exam-grade="{{ $exam->grade_level }}"
                                data-exam-duration="{{ $exam->duration_minutes }}"
                            >
                                <i class="bi bi-play-fill"></i>Mulai Ujian
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ===== MODAL TOKEN ===== --}}
<div class="modal fade" id="modalToken" tabindex="-1" aria-labelledby="modalTokenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalTokenLabel">
                        <i class="bi bi-key-fill text-primary me-2"></i>Masukkan Token Ujian
                    </h5>
                    <div class="text-muted small" id="modal-exam-name"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('student.exam.token') }}" method="POST" id="tokenForm">
                @csrf
                <input type="hidden" name="exam_id" id="modal-exam-id">

                <div class="modal-body">
                    {{-- Pilih Ruang --}}
                    <div class="mb-3">
                        <label for="room" class="form-label fw-semibold">
                            <i class="bi bi-door-open-fill text-primary me-1"></i>Pilih Ruang Ujian <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('room') is-invalid @enderror" id="room" name="room" required>
                            <option value="" disabled selected>-- Pilih Ruang Ujian --</option>
                            @for($r = 1; $r <= 20; $r++)
                                @php $rName = 'Ruang ' . sprintf('%02d', $r); @endphp
                                <option value="{{ $rName }}" {{ old('room') === $rName ? 'selected' : '' }}>{{ $rName }}</option>
                            @endfor
                            <option value="Lab Komputer 1" {{ old('room') === 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                            <option value="Lab Komputer 2" {{ old('room') === 'Lab Komputer 2' ? 'selected' : '' }}>Lab Komputer 2</option>
                            <option value="Lab Komputer 3" {{ old('room') === 'Lab Komputer 3' ? 'selected' : '' }}>Lab Komputer 3</option>
                            <option value="Lab Bahasa" {{ old('room') === 'Lab Bahasa' ? 'selected' : '' }}>Lab Bahasa</option>
                        </select>
                        @error('room')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Token Ujian --}}
                    <div class="mb-1">
                        <label for="token" class="form-label fw-semibold">
                            <i class="bi bi-shield-lock-fill text-primary me-1"></i>Token Ujian <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-lg token-input @error('token') is-invalid @enderror"
                            id="token"
                            name="token"
                            placeholder="XXXXXXXX"
                            value="{{ old('token') }}"
                            maxlength="16"
                            autocomplete="off"
                            required
                        >
                        @error('token')
                            <div class="invalid-feedback text-center">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i>Token diberikan oleh pengawas di ruang ujian
                    </div>

                    <div id="modal-exam-info" class="mt-3 p-3 rounded-3" style="background:#f8faff;border:1px solid #e0e7ff;">
                        <div class="d-flex gap-3">
                            <div class="text-center flex-fill">
                                <div class="fw-bold text-primary" id="modal-duration">—</div>
                                <div class="text-muted small">Durasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-unlock-fill me-1"></i>Mulai Ujian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter Tingkat Kelas (Tabs)
function filterGrade(gradeSlug, tabBtn) {
    document.querySelectorAll('.grade-tab').forEach(t => t.classList.remove('active'));
    if (tabBtn) tabBtn.classList.add('active');

    const sections = document.querySelectorAll('.grade-section');
    sections.forEach(sec => {
        if (gradeSlug === 'all') {
            sec.style.display = 'block';
        } else {
            if (sec.dataset.gradeSection === gradeSlug) {
                sec.style.display = 'block';
            } else {
                sec.style.display = 'none';
            }
        }
    });
}

// Populate modal with exam data when triggered
const modalEl = document.getElementById('modalToken');
modalEl.addEventListener('show.bs.modal', function (event) {
    const btn       = event.relatedTarget;
    const examId    = btn.dataset.examId;
    const examTitle = btn.dataset.examTitle;
    const duration  = btn.dataset.examDuration;
    const examGrade = btn.dataset.examGrade;

    document.getElementById('modal-exam-id').value   = examId;
    document.getElementById('modal-exam-name').textContent = (examGrade ? '[' + examGrade + '] ' : '') + examTitle;
    document.getElementById('modal-duration').textContent  = duration + ' menit';
});

// Auto-uppercase token input
document.getElementById('token').addEventListener('input', function () {
    this.value = this.value.toUpperCase().replace(/\s/g, '');
});

// Open modal if there are validation errors
@if($errors->has('token') || $errors->has('room'))
    const tokenModal = new bootstrap.Modal(document.getElementById('modalToken'));
    tokenModal.show();
@endif

// Live Clock (Real-time per detik sesuai jam laptop / device)
(function() {
    const clockEl = document.getElementById('liveServerClock');
    if (!clockEl) return;

    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockEl.textContent = hours + ':' + minutes + ':' + seconds;
    }

    updateClock();
    setInterval(updateClock, 1000);
})();
</script>
@endpush
