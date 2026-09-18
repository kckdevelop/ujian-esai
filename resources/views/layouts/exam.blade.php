<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lembar Ujian — {{ $exam->title ?? 'Ujian Online' }}</title>

    <!-- Bootstrap Icons only (minimal) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:  #4f46e5;
            --danger:   #ef4444;
            --success:  #10b981;
            --warning:  #f59e0b;
            --dark:     #0f172a;
            --radius:   10px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            height: 100vh;
            overflow: hidden;
            user-select: none;
        }

        /* ===== FLOATING TIMER ===== */
        #floating-timer {
            position: fixed;
            top: 14px;
            right: 18px;
            z-index: 200;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 14px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            transition: background .3s;
        }
        #floating-timer.warning {
            background: rgba(245, 158, 11, 0.92);
            border-color: rgba(245,158,11,.6);
            animation: pulse-warning 1s infinite;
        }
        #floating-timer.danger {
            background: rgba(239, 68, 68, 0.92);
            border-color: rgba(239,68,68,.6);
            animation: pulse-danger .6s infinite;
        }
        @keyframes pulse-warning {
            0%, 100% { box-shadow: 0 8px 32px rgba(245,158,11,.4); }
            50%       { box-shadow: 0 8px 48px rgba(245,158,11,.8); }
        }
        @keyframes pulse-danger {
            0%, 100% { box-shadow: 0 8px 32px rgba(239,68,68,.5); }
            50%       { box-shadow: 0 8px 48px rgba(239,68,68,.9); }
        }
        #timer-icon { font-size: 1.3rem; }
        #timer-display {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: 2px;
            font-variant-numeric: tabular-nums;
        }
        #timer-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: .75;
            line-height: 1;
        }

        /* ===== EXAM HEADER ===== */
        #exam-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 150;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.45) 60%, transparent 100%);
            padding: 12px 20px 28px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            pointer-events: none;
            transition: opacity .3s, transform .3s;
        }
        #exam-header > * {
            pointer-events: auto;
        }
        #exam-title {
            font-size: 1rem;
            font-weight: 700;
            color: #f8fafc;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 50vw;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }
        #exam-info {
            font-size: .82rem;
            color: rgba(248,250,252,.85);
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        /* ===== SLIDE VIEWER (FULL 1 LAYAR) ===== */
        #slide-container {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #020617;
            z-index: 10;
        }
        .slide {
            display: none;
            position: absolute;
            inset: 0;
            width: 100vw;
            height: 100vh;
            align-items: center;
            justify-content: center;
            animation: slideIn .25s ease;
        }
        .slide.active {
            display: flex;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: scale(.98); }
            to   { opacity: 1; transform: scale(1); }
        }
        .slide img {
            width: 100vw;
            height: 100vh;
            max-width: 100vw;
            max-height: 100vh;
            object-fit: contain;
            border-radius: 0;
            box-shadow: none;
        }
        /* Mode Fill (jika user memilih full stretch 100%) */
        .slide.fit-stretch img {
            object-fit: fill;
        }
        .slide.fit-cover img {
            object-fit: cover;
        }

        /* ===== NAV ARROWS ===== */
        .slide-nav-btn {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            z-index: 160;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            cursor: pointer;
            transition: background .2s, transform .2s, opacity .2s;
            box-shadow: 0 4px 16px rgba(0,0,0,.5);
        }
        .slide-nav-btn:hover {
            background: rgba(79, 70, 229, 0.9);
            transform: translateY(-50%) scale(1.1);
        }
        .slide-nav-btn:disabled {
            background: rgba(15, 23, 42, 0.25);
            opacity: .3;
            cursor: not-allowed;
            transform: translateY(-50%);
        }
        #btn-prev { left: 14px; }
        #btn-next { right: 14px; }

        /* ===== BOTTOM BAR ===== */
        #bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 150;
            background: linear-gradient(0deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.5) 60%, transparent 100%);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-top: none;
            padding: 24px 20px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            pointer-events: none;
            transition: opacity .3s, transform .3s;
        }
        #bottom-bar > * {
            pointer-events: auto;
        }

        /* Slide Dots / Thumbnails */
        #slide-dots {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
            flex: 1;
            padding: 0 10px;
        }
        .slide-dot {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255,255,255,.2);
            cursor: pointer;
            font-size: .75rem;
            font-weight: 700;
            color: rgba(248,250,252,.8);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            backdrop-filter: blur(4px);
        }
        .slide-dot:hover {
            background: rgba(79,70,229,.6);
            color: #fff;
            transform: translateY(-2px);
        }
        .slide-dot.active {
            background: var(--primary);
            border-color: #fff;
            color: #fff;
            transform: scale(1.08);
            box-shadow: 0 0 12px rgba(79,70,229,.7);
        }

        /* Finish Button */
        #btn-finish {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: .875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: transform .15s, box-shadow .15s;
            white-space: nowrap;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(239,68,68,.4);
        }
        #btn-finish:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(239,68,68,.6);
        }

        /* Slide Counter */
        #slide-counter {
            font-size: .85rem;
            font-weight: 700;
            color: rgba(248,250,252,.85);
            white-space: nowrap;
            flex-shrink: 0;
            min-width: 65px;
            text-shadow: 0 2px 4px rgba(0,0,0,.6);
        }

        /* Toggle Overlay mode (bersih / sembunyikan bar) */
        .ui-hidden #exam-header,
        .ui-hidden #bottom-bar,
        .ui-hidden .slide-nav-btn {
            opacity: 0;
            pointer-events: none !important;
            transform: translateY(-10px);
        }
        .ui-hidden #bottom-bar {
            transform: translateY(10px);
        }

        /* ===== MODALS ===== */
        .exam-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9990;
            background: rgba(0,0,0,.75);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }
        .exam-modal-overlay.show {
            opacity: 1;
            pointer-events: all;
        }
        .exam-modal {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 440px;
            width: 90%;
            text-align: center;
            box-shadow: 0 24px 80px rgba(0,0,0,.6);
        }
        .exam-modal .modal-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .exam-modal h3 { font-weight: 800; margin-bottom: .5rem; }
        .exam-modal p  { color: rgba(248,250,252,.65); font-size: .9rem; margin-bottom: 1.5rem; }
        .exam-modal .btn-group-modal {
            display: flex;
            gap: .75rem;
            justify-content: center;
        }
        .btn-modal-primary {
            background: var(--primary);
            border: none;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s;
        }
        .btn-modal-secondary {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-modal-secondary:hover { background: rgba(255,255,255,.18); }
        .btn-modal-danger {
            background: var(--danger);
            border: none;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        /* Fullscreen prompt */
        #fullscreen-prompt {
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            text-align: center;
            padding: 2rem;
        }
        #fullscreen-prompt h2 { font-size: 2rem; font-weight: 800; }
        #fullscreen-prompt p  { color: rgba(248,250,252,.65); max-width: 420px; }
        #btn-enter-fullscreen {
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border: none;
            color: #fff;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 8px 30px rgba(79,70,229,.5);
        }
        #btn-enter-fullscreen:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(79,70,229,.7);
        }

        /* Time-up modal */
        #modal-timeup .modal-icon { color: var(--danger); }
        #modal-confirm .modal-icon { color: var(--warning); }
    </style>

    @stack('styles')
</head>
<body>

{{-- Fullscreen Prompt (muncul pertama kali) --}}
<div id="fullscreen-prompt">
    <div style="font-size:5rem;">🖥️</div>
    <h2>Siap Memulai Ujian?</h2>
    <p>Ujian akan ditampilkan dalam mode <strong>Layar Penuh</strong>. Pastikan kamu tidak keluar dari halaman ini selama ujian berlangsung.</p>
    <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:1rem 1.5rem;max-width:380px;">
        <div style="font-size:.85rem;color:rgba(248,250,252,.6);">Paket Ujian</div>
        <div style="font-weight:800;font-size:1.2rem;">{{ $exam->title }}</div>
        <div style="font-size:.85rem;color:rgba(248,250,252,.5);margin-top:.25rem;">
            Durasi: <strong>{{ $exam->duration_minutes }} menit</strong> &bull;
            {{ $exam->images->count() }} halaman soal
        </div>
    </div>
    <button id="btn-enter-fullscreen">
        <i class="bi bi-fullscreen"></i> Masuk & Mulai Ujian
    </button>
    <div style="font-size:.78rem;color:rgba(248,250,252,.35);">Tekan Esc atau F11 tidak dianjurkan selama ujian</div>
</div>

{{-- ===== EXAM UI (hidden until fullscreen) ===== --}}
<div id="exam-ui" style="display:none;">

    {{-- Header --}}
    <div id="exam-header">
        <div>
            <div id="exam-title">
                {{ $exam->title }}
                @if($exam->grade_level)
                    <span style="font-size:.78rem;background:rgba(255,255,255,.18);padding:2px 8px;border-radius:6px;font-weight:600;margin-left:6px;">
                        🎓 {{ $exam->grade_level }}
                    </span>
                @endif
            </div>
            <div id="exam-info">
                @if($examSession->room)
                    <i class="bi bi-door-open-fill text-info me-1"></i>Ruang: <strong>{{ $examSession->room }}</strong>
                @endif
                @if($examSession->student_name)
                    &bull; Peserta: <strong>{{ $examSession->student_name }}</strong>
                @endif
            </div>
        </div>
    </div>

    {{-- Floating Timer & View Mode Buttons --}}
    <div id="floating-timer">
        <div id="timer-icon">⏱️</div>
        <div>
            <div id="timer-label">Sisa Waktu</div>
            <div id="timer-display">00:00:00</div>
        </div>
        {{-- Per-slide timer (tampil hanya jika soal punya durasi khusus) --}}
        <div id="slide-timer-wrap" style="display:none;border-left:1px solid rgba(255,255,255,.2);padding-left:12px;margin-left:4px;">
            <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;opacity:.7;line-height:1;">Soal ini</div>
            <div id="slide-timer-display" style="font-size:1.2rem;font-weight:800;letter-spacing:2px;font-variant-numeric:tabular-nums;">00:00</div>
        </div>
        {{-- Mode Rentang Penuh (Stretch/Fill vs Fit) --}}
        <div style="border-left:1px solid rgba(255,255,255,.2);padding-left:10px;margin-left:4px;display:flex;gap:6px;">
            <button id="btn-toggle-fit" class="btn btn-sm" style="background:rgba(255,255,255,.12);border:none;color:#fff;border-radius:8px;padding:4px 8px;font-size:.75rem;" title="Ubah Mode Layar: Proporsional / Rentang Penuh (1 Layar)">
                <i class="bi bi-arrows-fullscreen"></i> <span id="fit-mode-label">Fit</span>
            </button>
            <button id="btn-toggle-ui" class="btn btn-sm" style="background:rgba(255,255,255,.12);border:none;color:#fff;border-radius:8px;padding:4px 8px;font-size:.75rem;" title="Sembunyikan / Tampilkan Toolbar (Tekan H)">
                <i class="bi bi-eye-slash" id="ui-eye-icon"></i>
            </button>
        </div>
    </div>

    {{-- Slide Container --}}
    <div id="slide-container">
        @forelse($exam->images as $index => $image)
            <div class="slide {{ $index === 0 ? 'active' : '' }}"
                 data-index="{{ $index }}"
                 data-duration="{{ $image->duration_seconds ?? 0 }}">
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    alt="Soal {{ $index + 1 }}"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22><rect fill=%22%231e293b%22 width=%22400%22 height=%22300%22/><text x=%2250%%22 y=%2250%%22 font-size=%2218%22 fill=%22%2394a3b8%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22>Gambar tidak ditemukan</text></svg>'"
                >
            </div>
        @empty
            <div style="text-align:center;color:rgba(248,250,252,.4);">
                <div style="font-size:4rem;">📭</div>
                <div style="margin-top:1rem;font-size:1.1rem;">Belum ada gambar soal.</div>
            </div>
        @endforelse
    </div>

    {{-- Prev/Next Buttons --}}
    <button class="slide-nav-btn" id="btn-prev" title="Soal Sebelumnya" disabled>
        <i class="bi bi-chevron-left"></i>
    </button>
    <button class="slide-nav-btn" id="btn-next" title="Soal Berikutnya">
        <i class="bi bi-chevron-right"></i>
    </button>

    {{-- Bottom Bar --}}
    <div id="bottom-bar">
        <div id="slide-counter">1 / {{ $exam->images->count() }}</div>

        <div id="slide-dots">
            @foreach($exam->images as $index => $image)
                <div class="slide-dot {{ $index === 0 ? 'active' : '' }}"
                     data-index="{{ $index }}"
                     title="Soal {{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>
            @endforeach
        </div>

        <button id="btn-finish" onclick="showConfirmModal()">
            <i class="bi bi-check2-circle"></i> Selesai
        </button>
    </div>

</div>{{-- end #exam-ui --}}

{{-- ===== MODAL: Konfirmasi Selesai ===== --}}
<div class="exam-modal-overlay" id="modal-confirm">
    <div class="exam-modal">
        <div class="modal-icon">⚠️</div>
        <h3>Selesaikan Ujian?</h3>
        <p>Apakah kamu yakin ingin mengakhiri ujian sekarang? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="btn-group-modal">
            <button class="btn-modal-secondary" onclick="hideConfirmModal()">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button class="btn-modal-danger" onclick="finishExam('manual')">
                <i class="bi bi-check2-circle"></i> Ya, Selesaikan
            </button>
        </div>
    </div>
</div>

{{-- ===== MODAL: Waktu Habis ===== --}}
<div class="exam-modal-overlay" id="modal-timeup">
    <div class="exam-modal">
        <div class="modal-icon">⏰</div>
        <h3>Waktu Habis!</h3>
        <p>Waktu ujian telah berakhir. Ujian kamu akan diselesaikan secara otomatis.</p>
        <div class="btn-group-modal">
            <button class="btn-modal-primary" onclick="finishExam('auto')">
                <i class="bi bi-door-open"></i> OK, Keluar
            </button>
        </div>
    </div>
</div>

<script>
// ===== DATA dari Server =====
const REMAINING_SECONDS = Math.floor({{ $remainingSeconds }});
const FINISH_URL        = "{{ route('student.exam.finish', $exam->id) }}";
const FINISHED_URL      = "{{ route('student.exam.finished', $exam->id) }}";
const CSRF_TOKEN        = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const TOTAL_SLIDES      = {{ $exam->images->count() }};

// Data durasi per soal (dalam detik, 0 = tidak ada batas)
const SLIDE_DURATIONS = [
    @foreach($exam->images as $img)
        {{ $img->duration_seconds ?? 0 }},
    @endforeach
];

let currentSlide         = 0;
let timerInterval        = null;   // interval countdown ujian keseluruhan
let slideTimerInterval   = null;   // interval countdown per-soal
let remainingSeconds     = REMAINING_SECONDS;
let slideRemainingSeconds = 0;     // sisa detik soal aktif
let examFinished         = false;

// ===== FULLSCREEN =====
const fullscreenPrompt = document.getElementById('fullscreen-prompt');
const examUI           = document.getElementById('exam-ui');

document.getElementById('btn-enter-fullscreen').addEventListener('click', function () {
    const el = document.documentElement;
    const req = el.requestFullscreen
        || el.webkitRequestFullscreen
        || el.mozRequestFullScreen
        || el.msRequestFullscreen;
    if (req) req.call(el);

    fullscreenPrompt.style.display = 'none';
    examUI.style.display = 'block';

    startTimer();
    startSlideTimer(0); // mulai timer soal pertama
});

// Tangani jika user keluar dari fullscreen
document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
function handleFullscreenChange() {
    if (!document.fullscreenElement && !document.webkitFullscreenElement && !examFinished) {
        setTimeout(() => {
            alert('⚠️ Peringatan: Keluar dari layar penuh tidak diperbolehkan selama ujian! Klik OK untuk kembali ke mode penuh.');
            const el = document.documentElement;
            const req = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen;
            if (req) req.call(el);
        }, 100);
    }
}

// ===== TIMER KESELURUHAN UJIAN =====
function startTimer() {
    updateTimerDisplay();

    timerInterval = setInterval(function () {
        remainingSeconds--;

        if (remainingSeconds <= 0) {
            remainingSeconds = 0;
            updateTimerDisplay();
            clearInterval(timerInterval);
            clearInterval(slideTimerInterval);
            showTimeupModal();
            return;
        }

        updateTimerDisplay();

        // Warning states
        const timer = document.getElementById('floating-timer');
        if (remainingSeconds <= 60) {
            timer.className = 'danger';
        } else if (remainingSeconds <= 300) {
            timer.className = 'warning';
        } else {
            timer.className = '';
        }
    }, 1000);
}

function updateTimerDisplay() {
    const s   = Math.floor(remainingSeconds);
    const h   = Math.floor(s / 3600);
    const m   = Math.floor((s % 3600) / 60);
    const sec = s % 60;
    const fmt = n => String(n).padStart(2, '0');
    document.getElementById('timer-display').textContent =
        (h > 0 ? fmt(h) + ':' : '') + fmt(m) + ':' + fmt(sec);
}

// ===== TIMER PER-SOAL =====
function startSlideTimer(slideIndex) {
    // Hentikan timer soal sebelumnya
    clearInterval(slideTimerInterval);
    slideTimerInterval = null;

    const dur = SLIDE_DURATIONS[slideIndex] || 0;
    const wrap = document.getElementById('slide-timer-wrap');
    const disp = document.getElementById('slide-timer-display');

    if (!dur || dur <= 0) {
        // Soal ini tidak punya batas waktu khusus
        wrap.style.display = 'none';
        return;
    }

    // Tampilkan timer per-soal
    wrap.style.display = 'flex';
    wrap.style.flexDirection = 'column';
    wrap.style.justifyContent = 'center';
    slideRemainingSeconds = dur;
    updateSlideTimerDisplay();

    slideTimerInterval = setInterval(function () {
        slideRemainingSeconds--;

        if (slideRemainingSeconds <= 0) {
            slideRemainingSeconds = 0;
            updateSlideTimerDisplay();
            clearInterval(slideTimerInterval);
            slideTimerInterval = null;

            // Auto-advance ke soal berikutnya
            const next = currentSlide + 1;
            if (next < TOTAL_SLIDES) {
                // Flash singkat sebelum pindah
                wrap.style.color = '#ef4444';
                setTimeout(() => {
                    wrap.style.color = '';
                    goToSlide(next);
                }, 600);
            } else {
                // Soal terakhir habis — selesaikan ujian otomatis
                wrap.style.color = '#ef4444';
                setTimeout(() => showTimeupModal(), 600);
            }
            return;
        }

        updateSlideTimerDisplay();

        // Warning: 10 detik terakhir per soal
        if (slideRemainingSeconds <= 10) {
            disp.style.color = '#ef4444';
        } else if (slideRemainingSeconds <= 30) {
            disp.style.color = '#f59e0b';
        } else {
            disp.style.color = '';
        }
    }, 1000);
}

function updateSlideTimerDisplay() {
    const s   = Math.floor(slideRemainingSeconds);
    const m   = Math.floor(s / 60);
    const sec = s % 60;
    const fmt = n => String(n).padStart(2, '0');
    document.getElementById('slide-timer-display').textContent = fmt(m) + ':' + fmt(sec);
}

// ===== SLIDE NAVIGATION =====
function goToSlide(index) {
    if (index < 0 || index >= TOTAL_SLIDES) return;

    document.querySelectorAll('.slide').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.slide-dot').forEach(d => d.classList.remove('active'));

    const slides = document.querySelectorAll('.slide');
    const dots   = document.querySelectorAll('.slide-dot');

    if (slides[index]) slides[index].classList.add('active');
    if (dots[index])   dots[index].classList.add('active');

    currentSlide = index;

    document.getElementById('btn-prev').disabled = (index === 0);
    document.getElementById('btn-next').disabled = (index === TOTAL_SLIDES - 1);
    document.getElementById('slide-counter').textContent = `${index + 1} / ${TOTAL_SLIDES}`;

    // Scroll the dot into view
    if (dots[index]) dots[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

    // Mulai timer per-soal untuk soal yang baru ditampilkan
    startSlideTimer(index);
}

document.getElementById('btn-prev').addEventListener('click', () => goToSlide(currentSlide - 1));
document.getElementById('btn-next').addEventListener('click', () => goToSlide(currentSlide + 1));

document.querySelectorAll('.slide-dot').forEach(dot => {
    dot.addEventListener('click', function () {
        goToSlide(parseInt(this.dataset.index));
    });
});

// Keyboard navigation
document.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') goToSlide(currentSlide + 1);
    if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   goToSlide(currentSlide - 1);
    if (e.key === 'h' || e.key === 'H') toggleUIMode();
    if (e.key === 'f' || e.key === 'F') toggleFitMode();
});

// ===== VIEW MODES (FIT / STRETCH / CLEAN UI) =====
let currentFitMode = 'contain'; // 'contain' (Fit), 'stretch' (Fill 100%), 'cover' (Cover)
const fitModes = [
    { key: 'contain', label: 'Fit', class: '' },
    { key: 'stretch', label: 'Layar Penuh (100%)', class: 'fit-stretch' },
    { key: 'cover',   label: 'Cover', class: 'fit-cover' }
];
let fitModeIndex = 0;

function toggleFitMode() {
    fitModeIndex = (fitModeIndex + 1) % fitModes.length;
    const mode = fitModes[fitModeIndex];
    
    document.querySelectorAll('.slide').forEach(s => {
        s.classList.remove('fit-stretch', 'fit-cover');
        if (mode.class) s.classList.add(mode.class);
    });

    const label = document.getElementById('fit-mode-label');
    if (label) label.textContent = mode.label;
}

const btnToggleFit = document.getElementById('btn-toggle-fit');
if (btnToggleFit) {
    btnToggleFit.addEventListener('click', toggleFitMode);
}

// Clean / Hide Toolbar mode
let isUIHidden = false;
function toggleUIMode() {
    isUIHidden = !isUIHidden;
    document.body.classList.toggle('ui-hidden', isUIHidden);
    const eyeIcon = document.getElementById('ui-eye-icon');
    if (eyeIcon) {
        eyeIcon.className = isUIHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
}

const btnToggleUI = document.getElementById('btn-toggle-ui');
if (btnToggleUI) {
    btnToggleUI.addEventListener('click', toggleUIMode);
}

// Touch/swipe support
let touchStartX = 0;
document.getElementById('slide-container').addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
document.getElementById('slide-container').addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
        diff > 0 ? goToSlide(currentSlide + 1) : goToSlide(currentSlide - 1);
    }
});

// Initial state
goToSlide(0);

// ===== MODALS =====
function showConfirmModal() {
    document.getElementById('modal-confirm').classList.add('show');
}
function hideConfirmModal() {
    document.getElementById('modal-confirm').classList.remove('show');
}
function showTimeupModal() {
    document.getElementById('modal-timeup').classList.add('show');
}

// ===== FINISH EXAM =====
function finishExam(reason) {
    if (examFinished) return;
    examFinished = true;
    clearInterval(timerInterval);
    clearInterval(slideTimerInterval);

    // Keluar fullscreen
    if (document.exitFullscreen) document.exitFullscreen();
    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();

    // Kirim ke server via fetch
    fetch(FINISH_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({ reason }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(() => {
        window.location.href = FINISHED_URL;
    });
}
</script>
</body>
</html>
