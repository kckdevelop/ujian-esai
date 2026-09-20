<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamImage;
use App\Models\StudentExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    /**
     * Menampilkan daftar semua paket ujian.
     */
    public function index(Request $request)
    {
        $selectedGrade = $request->get('grade_level');

        $query = Exam::withCount('images', 'sessions')
                     ->orderByDesc('start_time');

        if ($selectedGrade && $selectedGrade !== 'all') {
            if ($selectedGrade === 'other') {
                $query->whereNull('grade_level')->orWhere('grade_level', '');
            } else {
                $query->where('grade_level', $selectedGrade);
            }
        }

        $exams = $query->paginate(15)->withQueryString();

        // Ambil daftar unik semua tingkat kelas yang pernah dibuat
        $allGradeLevels = Exam::whereNotNull('grade_level')
                              ->where('grade_level', '!=', '')
                              ->distinct()
                              ->pluck('grade_level')
                              ->sort()
                              ->values();

        return view('admin.exams.index', compact('exams', 'allGradeLevels', 'selectedGrade'));
    }

    /**
     * Menampilkan form pembuatan paket ujian baru.
     */
    public function create()
    {
        $existingGrades = Exam::whereNotNull('grade_level')
                              ->where('grade_level', '!=', '')
                              ->distinct()
                              ->pluck('grade_level');

        return view('admin.exams.create', compact('existingGrades'));
    }

    /**
     * Menyimpan paket ujian baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'grade_level'      => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'is_active'        => 'boolean',
        ]);

        $validated['token']     = strtoupper(Str::random(8));
        $validated['is_active'] = $request->boolean('is_active');

        $exam = Exam::create($validated);

        return redirect()
            ->route('admin.exams.edit', $exam)
            ->with('success', 'Paket ujian berhasil dibuat! Token: ' . $exam->token);
    }

    /**
     * Menampilkan form edit paket ujian beserta galeri gambar soal.
     */
    public function edit(Exam $exam)
    {
        $exam->load('images');
        $existingGrades = Exam::whereNotNull('grade_level')
                              ->where('grade_level', '!=', '')
                              ->distinct()
                              ->pluck('grade_level');

        return view('admin.exams.edit', compact('exam', 'existingGrades'));
    }

    /**
     * Menyimpan perubahan paket ujian.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'grade_level'      => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'token'            => 'required|string|max:32|unique:exams,token,' . $exam->id,
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $exam->update($validated);

        return redirect()
            ->route('admin.exams.edit', $exam)
            ->with('success', 'Paket ujian berhasil diperbarui!');
    }

    /**
     * Menghapus paket ujian beserta semua gambarnya.
     */
    public function destroy(Exam $exam)
    {
        // Hapus semua file gambar dari storage
        foreach ($exam->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Paket ujian berhasil dihapus.');
    }

    /**
     * Upload gambar soal ke paket ujian.
     */
    public function uploadImages(Request $request, Exam $exam)
    {
        try {
            // Tangani multiple files atau single file (images[] atau image)
            $files = [];
            if ($request->hasFile('images')) {
                $imagesInput = $request->file('images');
                $files = is_array($imagesInput) ? $imagesInput : [$imagesInput];
            } elseif ($request->hasFile('image')) {
                $files = [$request->file('image')];
            }

            if (empty($files)) {
                $errorMsg = 'Tidak ada file gambar yang diupload atau ukuran file melebihi batas server (post_max_size).';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg,
                    ], 422);
                }
                return back()->with('error', $errorMsg);
            }

            $request->validate([
                'default_duration_minutes' => 'nullable|numeric|min:0.1|max:360',
            ]);

            // Hitung durasi per soal (jika diisi)
            $defaultSeconds = null;
            if ($request->filled('default_duration_minutes') && (float) $request->default_duration_minutes > 0) {
                $defaultSeconds = (int) round((float) $request->default_duration_minutes * 60);
            }

            // Pastikan direktori tujuan tersedia
            $directory = 'exam-images/' . $exam->id;
            Storage::disk('public')->makeDirectory($directory);

            // Tentukan sort_order berikutnya
            $nextOrder = (int) ($exam->images()->max('sort_order') ?? 0) + 1;

            $uploaded = 0;
            $createdImages = [];

            foreach ($files as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $path = $file->store($directory, 'public');

                $newImage = ExamImage::create([
                    'exam_id'          => $exam->id,
                    'image_path'       => $path,
                    'sort_order'       => $nextOrder++,
                    'duration_seconds' => $defaultSeconds,
                ]);

                $createdImages[] = [
                    'id'               => $newImage->id,
                    'sort_order'       => $newImage->sort_order,
                    'image_path'       => $path,
                    'url'              => asset('storage/' . $path),
                    'duration_seconds' => $newImage->duration_seconds,
                ];
                $uploaded++;
            }

            if ($uploaded === 0) {
                $msg = 'File gambar tidak valid atau gagal disimpan.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
            }

            $durMsg = $defaultSeconds ? " dengan durasi {$request->default_duration_minutes} menit per soal." : ".";
            $msg = "{$uploaded} gambar soal berhasil diupload{$durMsg}";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'uploaded' => $uploaded,
                    'images'   => $createdImages,
                    'message'  => $msg,
                ]);
            }

            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            Log::error('Upload image failed for exam ' . $exam->id . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server saat upload: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat upload gambar: ' . $e->getMessage());
        }
    }

    /**
     * Update durasi tampil untuk semua soal sekaligus (batch update).
     */
    public function batchUpdateDuration(Request $request, Exam $exam)
    {
        $request->validate([
            'duration_minutes' => 'nullable|numeric|min:0|max:360',
        ]);

        $seconds = null;
        if ($request->filled('duration_minutes') && (float) $request->duration_minutes > 0) {
            $seconds = (int) round((float) $request->duration_minutes * 60);
        }

        $exam->images()->update(['duration_seconds' => $seconds]);

        return response()->json([
            'success'          => true,
            'duration_seconds' => $seconds,
            'message'          => $seconds
                                    ? "Semua soal berhasil diatur berdurasi {$request->duration_minutes} menit (" . ($seconds) . " detik)."
                                    : "Batas waktu per soal untuk semua soal telah dihapus.",
        ]);
    }

    /**
     * Update durasi tampil per-soal (dalam detik).
     * Nilai null = tidak ada batas waktu per soal.
     */
    public function updateImageDuration(Request $request, ExamImage $image)
    {
        $request->validate([
            'duration_seconds' => 'nullable|integer|min:0|max:86400',
        ]);

        $image->update([
            'duration_seconds' => ($request->filled('duration_seconds') && (int) $request->duration_seconds > 0)
                                    ? (int) $request->duration_seconds
                                    : null,
        ]);

        return response()->json([
            'success'          => true,
            'duration_seconds' => $image->duration_seconds,
            'message'          => $image->duration_seconds
                                    ? "Durasi soal diset ke {$image->duration_seconds} detik."
                                    : 'Batas waktu per soal dihapus.',
        ]);
    }

    /**
     * Menghapus satu gambar soal.
     */
    public function deleteImage(ExamImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Memperbarui urutan gambar soal (drag & drop sort).
     */
    public function reorderImages(Request $request, Exam $exam)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($request->order as $position => $imageId) {
            ExamImage::where('id', $imageId)
                     ->where('exam_id', $exam->id)
                     ->update(['sort_order' => $position + 1]);
        }

        return response()->json(['message' => 'Urutan gambar berhasil diperbarui.']);
    }

    /**
     * Regenerate token untuk paket ujian.
     */
    public function regenerateToken(Exam $exam)
    {
        $exam->update(['token' => strtoupper(Str::random(8))]);

        return back()->with('success', 'Token baru: ' . $exam->token);
    }

    /**
     * Menampilkan daftar peserta / sesi yang sudah memasukkan token ujian.
     */
    public function sessions(Request $request, Exam $exam)
    {
        $selectedRoom = $request->get('room');

        $query = $exam->sessions()->latest('started_at');

        if ($selectedRoom && $selectedRoom !== 'all') {
            if ($selectedRoom === 'other') {
                $query->whereNull('room')->orWhere('room', '');
            } else {
                $query->where('room', $selectedRoom);
            }
        }

        $sessions = $query->paginate(25)->withQueryString();

        // Daftar ruang unik yang ada di sesi ujian ini
        $availableRooms = $exam->sessions()
                              ->whereNotNull('room')
                              ->where('room', '!=', '')
                              ->distinct()
                              ->pluck('room')
                              ->sort()
                              ->values();

        // Statistik
        $totalParticipants = $exam->sessions()->count();
        $inProgressCount   = $exam->sessions()->where('is_finished', false)->count();
        $finishedCount     = $exam->sessions()->where('is_finished', true)->count();

        return view('admin.exams.sessions', compact(
            'exam',
            'sessions',
            'availableRooms',
            'selectedRoom',
            'totalParticipants',
            'inProgressCount',
            'finishedCount'
        ));
    }

    /**
     * Hapus / reset sesi peserta ujian (misal untuk mengizinkan login ulang).
     */
    public function destroySession(StudentExamSession $session)
    {
        $examId = $session->exam_id;
        $session->delete();

        return back()->with('success', 'Sesi peserta berhasil dihapus/direset.');
    }
}
