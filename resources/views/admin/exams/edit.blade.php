@extends('layouts.app')

@section('title', 'Edit Paket Ujian — ' . $exam->title)

@push('styles')
<style>
    .form-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .form-section-title {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border-color: #cbd5e1;
        padding: .65rem .9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.15);
    }
    .form-label { font-weight: 600; font-size: .875rem; color: #334155; }
    .hint-text { font-size: .78rem; color: #94a3b8; margin-top: .25rem; }
    .btn-save {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 1rem;
        transition: transform .15s, box-shadow .15s;
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(79,70,229,.4);
        color: #fff;
    }

    /* Token display */
    .token-display {
        font-family: 'Courier New', monospace;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 4px;
        color: #4f46e5;
        background: #ede9fe;
        border: 2px dashed #a5b4fc;
        border-radius: 12px;
        padding: .6rem 1.25rem;
        display: inline-block;
    }

    /* Upload zone */
    .upload-zone {
        border: 2px dashed #c7d2fe;
        border-radius: 14px;
        padding: 2rem 1.5rem;
        text-align: center;
        background: #f8faff;
        transition: border-color .2s, background .2s;
        cursor: pointer;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #4f46e5;
        background: #ede9fe;
    }
    .upload-zone-icon { font-size: 2.2rem; color: #818cf8; margin-bottom: .5rem; }
    #imageInput { display: none; }

    /* Question Item Card (List View) */
    .question-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .question-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
    }
    .question-thumb-wrap {
        width: 100px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: #e2e8f0;
        position: relative;
        flex-shrink: 0;
        cursor: pointer;
    }
    .question-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .question-badge {
        font-size: .8rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        background: #4f46e5;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .drag-handle {
        cursor: grab;
        color: #94a3b8;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        padding: 0 4px;
    }
    .drag-handle:hover { color: #4f46e5; }
    .duration-pill {
        font-size: .75rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .duration-pill.active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .duration-pill.no-limit {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Quick Preset Buttons */
    .btn-preset {
        font-size: .72rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-preset:hover {
        background: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
    }

    /* Batch bar */
    .batch-bar {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }

    /* Toggle switch */
    .toggle-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .toggle-switch .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }

    .sortable-ghost {
        opacity: .35;
        background: #e0e7ff;
        border: 2px dashed #4f46e5;
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width: 1140px;">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <div>
                <h1 class="h3 fw-bold mb-0">Edit Paket Ujian</h1>
                <div class="text-muted small">{{ $exam->title }}</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.exams.sessions', $exam) }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
                <i class="bi bi-people-fill me-1"></i>Detail Peserta ({{ $exam->sessions()->count() }})
            </a>
            <a href="{{ route('student.exam.show', $exam->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                <i class="bi bi-eye me-1"></i>Pratinjau Ujian
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- ===== Kolom Kiri: Informasi Ujian ===== --}}
        <div class="col-lg-5">

            {{-- Token --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-key text-warning"></i> Token Akses Siswa
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="token-display" id="tokenDisplay">{{ $exam->token }}</div>
                    <div>
                        <div class="hint-text mb-2">Token digunakan siswa untuk masuk ujian</div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:7px;"
                                    onclick="copyToken('{{ $exam->token }}')">
                                <i class="bi bi-clipboard me-1"></i>Salin
                            </button>
                            <form action="{{ route('admin.exams.regenerate-token', $exam) }}" method="POST"
                                  onsubmit="return confirm('Regenerasi token? Token lama tidak akan berlaku lagi.')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning" style="border-radius:7px;">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Regenerasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Update Ujian --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-info-circle text-primary"></i> Pengaturan Umum Ujian
                </div>

                <form action="{{ route('admin.exams.update', $exam) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label for="grade_level" class="form-label">Tingkat Kelas <span class="text-muted">(Pilih / Ketik)</span></label>
                            <input type="text" list="gradeOptionsEdit" class="form-control @error('grade_level') is-invalid @enderror"
                                   id="grade_level" name="grade_level" value="{{ old('grade_level', $exam->grade_level) }}"
                                   placeholder="Contoh: Kelas 10">
                            <datalist id="gradeOptionsEdit">
                                <option value="Kelas 10">
                                <option value="Kelas 11">
                                <option value="Kelas 12">
                                <option value="Kelas 7">
                                <option value="Kelas 8">
                                <option value="Kelas 9">
                                <option value="Kelas 1">
                                <option value="Kelas 2">
                                <option value="Kelas 3">
                                <option value="Kelas 4">
                                <option value="Kelas 5">
                                <option value="Kelas 6">
                                @foreach($existingGrades as $grade)
                                    <option value="{{ $grade }}">
                                @endforeach
                            </datalist>
                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                <span class="text-muted" style="font-size:.7rem;">Cepat:</span>
                                <a href="javascript:void(0)" class="badge bg-light text-dark border text-decoration-none" onclick="document.getElementById('grade_level').value='Kelas 10'">Kelas 10</a>
                                <a href="javascript:void(0)" class="badge bg-light text-dark border text-decoration-none" onclick="document.getElementById('grade_level').value='Kelas 11'">Kelas 11</a>
                                <a href="javascript:void(0)" class="badge bg-light text-dark border text-decoration-none" onclick="document.getElementById('grade_level').value='Kelas 12'">Kelas 12</a>
                            </div>
                            @error('grade_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi / Petunjuk</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="2">{{ old('description', $exam->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="token" class="form-label">Token Manual <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('token') is-invalid @enderror"
                               id="token" name="token" value="{{ old('token', $exam->token) }}"
                               maxlength="32" style="font-family:monospace;letter-spacing:2px;text-transform:uppercase;"
                               oninput="this.value = this.value.toUpperCase()">
                        @error('token')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                   id="start_time" name="start_time"
                                   value="{{ old('start_time', $exam->start_time->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                   id="end_time" name="end_time"
                                   value="{{ old('end_time', $exam->end_time->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="duration_minutes" class="form-label">Durasi Total Ujian <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                       id="duration_minutes" name="duration_minutes"
                                       value="{{ old('duration_minutes', $exam->duration_minutes) }}"
                                       min="1" max="600" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                            <div class="hint-text">Batas waktu keseluruhan sesi ujian</div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="toggle-switch form-check form-switch mt-2 ps-5">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                       name="is_active" value="1" {{ $exam->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Ujian Aktif</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-save w-100">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan Informasi
                    </button>
                </form>
            </div>

        </div>

        {{-- ===== Kolom Kanan: Upload & Pengaturan Durasi Per-Soal ===== --}}
        <div class="col-lg-7">

            {{-- Upload Section --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-cloud-upload text-success"></i> Upload Gambar Soal Baru
                </div>

                <form action="{{ route('admin.exams.images.upload', $exam) }}" method="POST"
                      enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="upload-zone mb-3" id="uploadZone" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-zone-icon">
                            <i class="bi bi-images"></i>
                        </div>
                        <div class="fw-bold mb-1 fs-6">Klik atau seret file gambar soal ke sini</div>
                        <div class="text-muted small">JPG, PNG, WebP — Maksimal 5MB per file</div>
                        <div class="text-muted small">Bisa pilih banyak gambar sekaligus</div>
                    </div>

                    <input type="file" id="imageInput" name="images[]" multiple
                           accept="image/jpeg,image/jpg,image/png,image/webp">

                    {{-- Default duration for uploaded files --}}
                    <div class="row g-2 align-items-center bg-light p-2 rounded-3 border">
                        <div class="col-sm-7">
                            <label class="form-label mb-0 small fw-bold text-secondary">
                                <i class="bi bi-stopwatch me-1 text-primary"></i>Set durasi otomatis untuk gambar baru:
                            </label>
                            <div class="text-muted" style="font-size:.72rem;">Opsional: misal 5 menit tiap soal</div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.5" min="0.1" max="180" name="default_duration_minutes"
                                       class="form-control form-control-sm" placeholder="Contoh: 5">
                                <span class="input-group-text">Menit/soal</span>
                            </div>
                        </div>
                    </div>

                    {{-- Preview area --}}
                    <div id="previewArea" class="mt-3 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-semibold small text-primary">
                                <i class="bi bi-check-circle me-1"></i><span id="fileCount"></span> gambar siap diupload:
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="resetUploadSelection()">
                                <i class="bi bi-trash me-1"></i>Batal / Reset
                            </button>
                        </div>
                        <div id="previewGrid" class="d-flex gap-2 flex-wrap mb-3" style="max-height: 200px; overflow-y: auto; padding: 4px;"></div>

                        {{-- Progress Area --}}
                        <div id="uploadProgressArea" class="mb-3 d-none bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold text-dark" id="uploadStatusText">Mempersiapkan upload...</span>
                                <span class="small fw-bold text-primary" id="uploadPercentText">0%</span>
                            </div>
                            <div class="progress mb-2" style="height: 12px; border-radius: 6px;">
                                <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div id="uploadLog" class="small" style="max-height: 80px; overflow-y: auto;"></div>
                        </div>

                        <button type="submit" id="startUploadBtn" class="btn btn-success w-100" style="border-radius:10px;font-weight:700;">
                            <i class="bi bi-cloud-upload me-1"></i>Mulai Upload Gambar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Pengaturan Durasi & Urutan Soal --}}
            <div class="form-section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="form-section-title mb-0 flex-grow-1">
                        <i class="bi bi-list-check text-primary"></i>
                        Daftar Soal & Pengaturan Durasi Per-Soal
                        <span class="badge bg-primary ms-1 fs-7">{{ $exam->images->count() }} Soal</span>
                    </div>
                </div>

                @if($exam->images->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <div style="font-size:3rem;">📭</div>
                        <div class="mt-2 fw-semibold">Belum ada gambar soal.</div>
                        <div class="small text-muted">Upload gambar soal pada kotak di atas untuk mulai mengatur durasi per-nomor soal.</div>
                    </div>
                @else

                    {{-- Batch Action: Terapkan ke semua soal --}}
                    <div class="batch-bar">
                        <div class="fw-bold small text-primary mb-2">
                            <i class="bi bi-lightning-charge-fill me-1"></i>Atur Cepat Durasi SEMUA Soal Sekaligus
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="input-group input-group-sm" style="max-width: 170px;">
                                <input type="number" id="batchMinutesInput" class="form-control" placeholder="5" min="0.1" step="0.5" value="5">
                                <span class="input-group-text">Menit</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="applyBatchDuration()">
                                <i class="bi bi-check-all me-1"></i>Terapkan ke Semua Soal
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllDurations()">
                                <i class="bi bi-x-circle me-1"></i>Hapus Batas Waktu
                            </button>
                        </div>
                        <div class="text-muted mt-1" style="font-size:.73rem;">
                            Kamu juga dapat mengatur durasi yang berbeda untuk tiap nomor soal di bawah ini.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="hint-text mb-0">
                            <i class="bi bi-arrows-move me-1"></i>Seret untuk ubah urutan nomor soal
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="saveOrderBtn" style="border-radius:8px;">
                            <i class="bi bi-save me-1"></i>Simpan Urutan
                        </button>
                    </div>

                    {{-- List Soal --}}
                    <div id="sortableQuestionsList">
                        @foreach($exam->images as $image)
                        @php
                            $durSecs = $image->duration_seconds;
                            $durMins = $durSecs ? round($durSecs / 60, 2) : '';
                        @endphp
                        <div class="question-card" data-id="{{ $image->id }}" id="q-card-{{ $image->id }}">
                            <div class="d-flex gap-3 align-items-start">
                                
                                {{-- Drag handle & Number Badge --}}
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <div class="drag-handle" title="Seret untuk memindahkan nomor">
                                        <i class="bi bi-grip-vertical"></i>
                                    </div>
                                    <div class="question-badge q-order-badge" id="order-badge-{{ $image->id }}">
                                        #{{ $image->sort_order }}
                                    </div>
                                </div>

                                {{-- Thumbnail --}}
                                <div class="question-thumb-wrap" onclick="openPreviewModal('{{ asset('storage/' . $image->image_path) }}', 'Soal #{{ $image->sort_order }}')" title="Klik untuk memperbesar">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Soal {{ $image->sort_order }}" loading="lazy">
                                    <div style="position:absolute;bottom:3px;right:3px;background:rgba(0,0,0,.6);color:#fff;border-radius:4px;padding:1px 4px;font-size:.65rem;">
                                        <i class="bi bi-zoom-in"></i>
                                    </div>
                                </div>

                                {{-- Setting Durasi Per Soal --}}
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1 flex-wrap gap-2">
                                        <div class="fw-bold text-dark fs-6">
                                            Soal Nomor <span class="q-order-text">{{ $image->sort_order }}</span>
                                        </div>

                                        {{-- Badge Status Durasi --}}
                                        <div id="dur-pill-{{ $image->id }}" class="duration-pill {{ $durSecs ? 'active' : 'no-limit' }}">
                                            @if($durSecs)
                                                <i class="bi bi-stopwatch-fill"></i>
                                                <span>{{ $durSecs >= 60 ? floor($durSecs/60).' menit '.($durSecs%60 > 0 ? ($durSecs%60).' detik' : '') : $durSecs.' detik' }}</span>
                                            @else
                                                <i class="bi bi-infinity"></i> <span>Tanpa batas (bebas)</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Preset Durasi Cepat --}}
                                    <div class="d-flex gap-1 align-items-center mb-2 flex-wrap">
                                        <span class="text-muted" style="font-size:.72rem;">Set Cepat:</span>
                                        <button type="button" class="btn-preset" onclick="setPresetDuration({{ $image->id }}, 1)">1 mnt</button>
                                        <button type="button" class="btn-preset" onclick="setPresetDuration({{ $image->id }}, 2)">2 mnt</button>
                                        <button type="button" class="btn-preset" onclick="setPresetDuration({{ $image->id }}, 3)">3 mnt</button>
                                        <button type="button" class="btn-preset" onclick="setPresetDuration({{ $image->id }}, 5)">5 mnt</button>
                                        <button type="button" class="btn-preset" onclick="setPresetDuration({{ $image->id }}, 10)">10 mnt</button>
                                        <button type="button" class="btn-preset text-danger" onclick="setPresetDuration({{ $image->id }}, 0)">Bebas (∞)</button>
                                    </div>

                                    {{-- Input Form Durasi --}}
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm" style="width: 140px;">
                                                <input type="number" step="0.5" min="0.1" max="180"
                                                       class="form-control form-control-sm fw-bold"
                                                       id="dur-min-input-{{ $image->id }}"
                                                       placeholder="Menit"
                                                       value="{{ $durMins }}"
                                                       onchange="calcSecondsFromMinutes({{ $image->id }})">
                                                <span class="input-group-text">Menit</span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm" style="width: 140px;">
                                                <input type="number" step="1" min="5" max="10800"
                                                       class="form-control form-control-sm text-secondary"
                                                       id="dur-sec-input-{{ $image->id }}"
                                                       placeholder="Detik"
                                                       value="{{ $durSecs ?? '' }}"
                                                       onchange="calcMinutesFromSeconds({{ $image->id }})">
                                                <span class="input-group-text">Detik</span>
                                            </div>
                                        </div>
                                        <div class="col-auto d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-success fw-bold" id="btn-save-{{ $image->id }}"
                                                    onclick="saveQuestionDuration({{ $image->id }})" title="Simpan durasi soal ini">
                                                <i class="bi bi-check-lg"></i> Simpan
                                            </button>
                                            <form action="{{ route('admin.images.delete', $image) }}" method="POST"
                                                  onsubmit="return confirm('Hapus gambar soal nomor ini?')" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus soal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                @endif
            </div>

        </div>
    </div>
</div>

{{-- Modal Image Preview --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-2 px-3">
                <h6 class="modal-title fw-bold" id="imagePreviewTitle">Pratinjau Gambar Soal</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2 text-center bg-dark rounded-bottom">
                <img id="imagePreviewSrc" src="" alt="Pratinjau" style="max-width:100%;max-height:80vh;object-fit:contain;border-radius:6px;">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- SortableJS for drag-and-drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const DURATION_URL = '{{ url("admin/images") }}';
const BATCH_URL    = '{{ route("admin.exams.images.batch-duration", $exam) }}';
const REORDER_URL  = '{{ route("admin.exams.images.reorder", $exam) }}';
const CSRF_TOKEN   = '{{ csrf_token() }}';

// Copy token
function copyToken(token) {
    navigator.clipboard.writeText(token).then(() => {
        alert('Token "' + token + '" berhasil disalin!');
    });
}

// Image Zoom Modal
function openPreviewModal(src, title) {
    document.getElementById('imagePreviewSrc').src = src;
    document.getElementById('imagePreviewTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

// Convert minutes input to seconds
function calcSecondsFromMinutes(id) {
    const minInput = document.getElementById('dur-min-input-' + id);
    const secInput = document.getElementById('dur-sec-input-' + id);
    const val = parseFloat(minInput.value);
    if (!isNaN(val) && val > 0) {
        secInput.value = Math.round(val * 60);
    } else {
        secInput.value = '';
    }
}

// Convert seconds input to minutes
function calcMinutesFromSeconds(id) {
    const minInput = document.getElementById('dur-min-input-' + id);
    const secInput = document.getElementById('dur-sec-input-' + id);
    const val = parseInt(secInput.value);
    if (!isNaN(val) && val > 0) {
        minInput.value = Math.round((val / 60) * 100) / 100;
    } else {
        minInput.value = '';
    }
}

// Preset Quick Buttons
function setPresetDuration(id, minutes) {
    const minInput = document.getElementById('dur-min-input-' + id);
    const secInput = document.getElementById('dur-sec-input-' + id);
    if (minutes > 0) {
        minInput.value = minutes;
        secInput.value = minutes * 60;
    } else {
        minInput.value = '';
        secInput.value = '';
    }
    saveQuestionDuration(id);
}

// Save individual question duration
function saveQuestionDuration(id) {
    const secInput = document.getElementById('dur-sec-input-' + id);
    const btn      = document.getElementById('btn-save-' + id);
    const pill     = document.getElementById('dur-pill-' + id);
    let seconds    = parseInt(secInput.value);

    if (isNaN(seconds) || seconds <= 0) {
        seconds = null;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(DURATION_URL + '/' + id + '/duration', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ duration_seconds: seconds })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disimpan';
            btn.classList.replace('btn-success', 'btn-primary');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan';
                btn.classList.replace('btn-primary', 'btn-success');
            }, 1800);

            // Update Pill
            if (data.duration_seconds && data.duration_seconds > 0) {
                const s = data.duration_seconds;
                const m = Math.floor(s / 60);
                const remS = s % 60;
                pill.className = 'duration-pill active';
                pill.innerHTML = `<i class="bi bi-stopwatch-fill"></i> <span>${m > 0 ? m + ' menit ' : ''}${remS > 0 ? remS + ' detik' : ''}</span>`;
            } else {
                pill.className = 'duration-pill no-limit';
                pill.innerHTML = '<i class="bi bi-infinity"></i> <span>Tanpa batas (bebas)</span>';
            }
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Gagal';
        setTimeout(() => { btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan'; }, 2000);
    });
}

// Apply Batch Duration to ALL questions
function applyBatchDuration() {
    const minVal = parseFloat(document.getElementById('batchMinutesInput').value);
    if (isNaN(minVal) || minVal <= 0) {
        alert('Masukkan jumlah menit yang valid (contoh: 5).');
        return;
    }

    if (!confirm(`Atur durasi SEMUA soal menjadi ${minVal} menit?`)) return;

    fetch(BATCH_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ duration_minutes: minVal })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

// Clear all durations
function clearAllDurations() {
    if (!confirm('Hapus batas waktu per-soal dari semua soal?')) return;

    fetch(BATCH_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ duration_minutes: null })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

// ===== Upload Zone Handling =====
const uploadZone  = document.getElementById('uploadZone');
const imageInput  = document.getElementById('imageInput');
const previewArea = document.getElementById('previewArea');
const previewGrid = document.getElementById('previewGrid');
const fileCountEl = document.getElementById('fileCount');

if (uploadZone) {
    uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', e => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        imageInput.files = e.dataTransfer.files;
        showPreviews(e.dataTransfer.files);
    });

    imageInput.addEventListener('change', function () {
        showPreviews(this.files);
    });
}

function resetUploadSelection() {
    imageInput.value = '';
    previewGrid.innerHTML = '';
    previewArea.classList.add('d-none');
    document.getElementById('uploadProgressArea').classList.add('d-none');
    const startBtn = document.getElementById('startUploadBtn');
    startBtn.disabled = false;
    startBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Mulai Upload Gambar';
}

function showPreviews(files) {
    if (!files.length) return;
    previewGrid.innerHTML = '';
    previewArea.classList.remove('d-none');
    document.getElementById('uploadProgressArea').classList.add('d-none');
    fileCountEl.textContent = files.length;

    Array.from(files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const div = document.createElement('div');
            div.style.width = '70px';
            div.style.height = '85px';
            div.style.borderRadius = '8px';
            div.style.overflow = 'hidden';
            div.style.border = '1px solid #cbd5e1';
            div.style.position = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${i+1}" style="width:100%;height:100%;object-fit:cover;">
                <div style="position:absolute;top:2px;left:2px;background:rgba(79,70,229,.9);color:#fff;font-size:.65rem;font-weight:bold;padding:1px 5px;border-radius:4px;">${i+1}</div>
            `;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// Intercept form submit to upload files sequentially (avoids PHP post_max_size / timeout limits)
const uploadForm         = document.getElementById('uploadForm');
const uploadProgressArea = document.getElementById('uploadProgressArea');
const uploadProgressBar  = document.getElementById('uploadProgressBar');
const uploadStatusText   = document.getElementById('uploadStatusText');
const uploadPercentText  = document.getElementById('uploadPercentText');
const uploadLog          = document.getElementById('uploadLog');
const startUploadBtn     = document.getElementById('startUploadBtn');

if (uploadForm) {
    uploadForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const files = imageInput.files;
        if (!files || files.length === 0) {
            alert('Silakan pilih minimal 1 file gambar soal.');
            return;
        }

        const defaultDuration = uploadForm.querySelector('[name="default_duration_minutes"]').value;
        const totalFiles = files.length;
        let successCount = 0;
        let failedCount = 0;

        startUploadBtn.disabled = true;
        startUploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupload...';
        uploadProgressArea.classList.remove('d-none');
        uploadProgressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
        uploadLog.innerHTML = '';

        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            const num = i + 1;
            const percent = Math.round((i / totalFiles) * 100);

            uploadStatusText.textContent = `Mengupload soal ${num} dari ${totalFiles} (${file.name})...`;
            uploadProgressBar.style.width = percent + '%';
            uploadPercentText.textContent = percent + '%';

            const formData = new FormData();
            formData.append('images[]', file);
            formData.append('_token', CSRF_TOKEN);
            if (defaultDuration) {
                formData.append('default_duration_minutes', defaultDuration);
            }

            try {
                const response = await fetch(uploadForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData
                });

                const data = await response.json().catch(() => null);
                if (response.ok && data && data.success) {
                    successCount++;
                } else {
                    failedCount++;
                    const errMsg = (data && data.message) ? data.message : `Error HTTP ${response.status}`;
                    uploadLog.innerHTML += `<div class="text-danger small mb-1"><i class="bi bi-exclamation-triangle me-1"></i>${file.name}: ${errMsg}</div>`;
                }
            } catch (err) {
                failedCount++;
                uploadLog.innerHTML += `<div class="text-danger small mb-1"><i class="bi bi-x-circle me-1"></i>${file.name}: Gagal koneksi (${err.message})</div>`;
            }

            const currPercent = Math.round(((i + 1) / totalFiles) * 100);
            uploadProgressBar.style.width = currPercent + '%';
            uploadPercentText.textContent = currPercent + '%';
        }

        uploadProgressBar.classList.remove('progress-bar-animated', 'progress-bar-striped');

        if (failedCount === 0) {
            uploadProgressBar.classList.remove('bg-warning');
            uploadProgressBar.classList.add('bg-success');
            uploadStatusText.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Berhasil! ${successCount} gambar soal selesai diupload. Memuat ulang...</span>`;
            setTimeout(() => {
                window.location.reload();
            }, 1200);
        } else {
            uploadProgressBar.classList.replace('bg-success', 'bg-warning');
            uploadStatusText.innerHTML = `<span class="text-warning fw-bold">${successCount} berhasil diupload, ${failedCount} gagal.</span>`;
            startUploadBtn.disabled = false;
            startUploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Ulang';

            if (!document.getElementById('reloadAfterUploadBtn')) {
                const reloadBtn = document.createElement('button');
                reloadBtn.id = 'reloadAfterUploadBtn';
                reloadBtn.type = 'button';
                reloadBtn.className = 'btn btn-primary btn-sm mt-2 w-100';
                reloadBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang Halaman untuk Lihat Soal yang Berhasil';
                reloadBtn.onclick = () => window.location.reload();
                uploadProgressArea.appendChild(reloadBtn);
            }
        }
    });
}

// ===== Sortable Questions Reorder =====
const sortableListEl = document.getElementById('sortableQuestionsList');
if (sortableListEl) {
    Sortable.create(sortableListEl, {
        animation: 200,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: function () {
            // Update visual badge numbers
            sortableListEl.querySelectorAll('.question-card').forEach((card, i) => {
                const num = i + 1;
                const badge = card.querySelector('.q-order-badge');
                const text  = card.querySelector('.q-order-text');
                if (badge) badge.textContent = '#' + num;
                if (text)  text.textContent  = num;
            });
        }
    });

    document.getElementById('saveOrderBtn').addEventListener('click', function () {
        const ids = Array.from(sortableListEl.querySelectorAll('.question-card'))
                        .map(el => parseInt(el.dataset.id));
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

        fetch(REORDER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ order: ids })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = '✓ Urutan Tersimpan!';
            btn.classList.replace('btn-outline-primary', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Urutan';
                btn.classList.replace('btn-success', 'btn-outline-primary');
            }, 2000);
        });
    });
}
</script>
@endpush

