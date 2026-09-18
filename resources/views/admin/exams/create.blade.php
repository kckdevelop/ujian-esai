@extends('layouts.app')

@section('title', 'Tambah Paket Ujian')

@push('styles')
<style>
    .form-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .form-section-title {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
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
        border-color: #e2e8f0;
        padding: .65rem .9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    }
    .form-label { font-weight: 600; font-size: .875rem; color: #374151; }
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
    .toggle-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .toggle-switch .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width: 760px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <div>
            <h1 class="h3 fw-bold mb-0">Tambah Paket Ujian Baru</h1>
            <div class="text-muted small">Isi form berikut untuk membuat paket soal ujian</div>
        </div>
    </div>

    <form action="{{ route('admin.exams.store') }}" method="POST">
        @csrf

        {{-- Informasi Dasar --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-info-circle text-primary"></i> Informasi Ujian
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-7">
                    <label for="title" class="form-label">Judul Paket Ujian <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title') }}"
                           placeholder="Contoh: Penilaian Akhir Matematika"
                           required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5">
                    <label for="grade_level" class="form-label">Tingkat Kelas <span class="text-muted">(Pilih / Ketik)</span></label>
                    <input type="text" list="gradeOptions" class="form-control @error('grade_level') is-invalid @enderror"
                           id="grade_level" name="grade_level" value="{{ old('grade_level') }}"
                           placeholder="Contoh: Kelas 10, Kelas 11, Kelas 12">
                    <datalist id="gradeOptions">
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

            <div class="mb-0">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="2"
                          placeholder="Opsional: keterangan tambahan tentang ujian ini">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Jadwal --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-calendar3 text-primary"></i> Jadwal Pelaksanaan
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                           id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                           id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="duration_minutes" class="form-label">Durasi Pengerjaan (menit) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                               id="duration_minutes" name="duration_minutes"
                               value="{{ old('duration_minutes', 60) }}" min="1" max="600" required>
                        <span class="input-group-text">menit</span>
                    </div>
                    <div class="hint-text">Waktu yang diberikan untuk siswa mengerjakan soal</div>
                    @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="toggle-switch form-check form-switch mt-3 ps-5">
                        <input class="form-check-input" type="checkbox" id="is_active"
                               name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Status Aktif
                        </label>
                        <div class="hint-text">Ujian hanya terlihat siswa jika status aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:10px;">
                Batal
            </a>
            <button type="submit" class="btn btn-save">
                <i class="bi bi-check-lg me-1"></i>Simpan & Tambahkan Soal
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
// Auto-set end_time = start_time + duration when duration changes
const startInput    = document.getElementById('start_time');
const endInput      = document.getElementById('end_time');
const durationInput = document.getElementById('duration_minutes');

function updateEndTime() {
    if (startInput.value && durationInput.value) {
        const start = new Date(startInput.value);
        start.setMinutes(start.getMinutes() + parseInt(durationInput.value) + 30); // Buffer 30min
        endInput.value = start.toISOString().slice(0, 16);
    }
}
startInput.addEventListener('change', updateEndTime);
durationInput.addEventListener('change', updateEndTime);
</script>
@endpush
