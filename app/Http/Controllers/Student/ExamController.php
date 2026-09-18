<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    /**
     * Halaman utama — daftar ujian yang aktif & berjalan hari ini.
     */
    public function index(Request $request)
    {
        $exams = Exam::where('is_active', true)
                     ->where('start_time', '<=', now())
                     ->where('end_time', '>=', now())
                     ->withCount('images')
                     ->orderBy('grade_level')
                     ->orderBy('start_time')
                     ->get();

        // Kelompokkan per tingkat kelas
        $groupedExams = $exams->groupBy(function ($exam) {
            return $exam->grade_level ?: 'Umum / Lainnya';
        });

        // Daftar tingkat kelas yang tersedia saat ini
        $gradeLevels = $exams->pluck('grade_level')->filter()->unique()->values();

        return view('student.index', compact('exams', 'groupedExams', 'gradeLevels'));
    }

    /**
     * Validasi token & buat sesi ujian siswa.
     */
    public function validateToken(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'room'    => 'required|string|max:50',
            'token'   => 'required|string',
        ], [
            'room.required'  => 'Silakan pilih ruang ujian Anda.',
            'token.required' => 'Silakan masukkan token ujian.',
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        // Cek apakah ujian masih berlangsung
        if (!$exam->is_active || now()->lt($exam->start_time) || now()->gt($exam->end_time)) {
            return back()->withErrors(['token' => 'Ujian tidak tersedia atau sudah berakhir.'])
                         ->withInput();
        }

        // Validasi token (case-insensitive)
        if (strtoupper(trim($request->token)) !== strtoupper($exam->token)) {
            return back()->withErrors(['token' => 'Token tidak valid. Periksa kembali token ujian Anda.'])
                         ->withInput();
        }

        // Buat sesi ujian baru
        $session = StudentExamSession::create([
            'user_id'     => auth()->id(),
            'exam_id'     => $exam->id,
            'room'        => $request->room,
            'started_at'  => now(),
            'is_finished' => false,
        ]);

        // Simpan session ID di Laravel session untuk keamanan
        Session::put('exam_session_id', $session->id);
        Session::put('exam_session_exam_id', $exam->id);

        return redirect()->route('student.exam.show', $exam->id);
    }

    /**
     * Halaman lembar soal ujian (fullscreen + slide gambar + timer).
     */
    public function show(Request $request, int $examId)
    {
        $exam = Exam::with('images')->findOrFail($examId);

        // Ambil sesi dari Laravel session
        $sessionId = Session::get('exam_session_id');

        if (!$sessionId) {
            return redirect()->route('student.index')
                             ->withErrors(['token' => 'Silakan masukkan token untuk memulai ujian.']);
        }

        $examSession = StudentExamSession::where('id', $sessionId)
                                         ->where('exam_id', $examId)
                                         ->first();

        if (!$examSession) {
            return redirect()->route('student.index')
                             ->withErrors(['token' => 'Sesi ujian tidak ditemukan. Silakan mulai ulang.']);
        }

        // Jika sudah selesai, arahkan ke halaman selesai
        if ($examSession->is_finished) {
            return redirect()->route('student.exam.finished', $examId);
        }

        // Cek apakah waktu ujian sudah habis
        $elapsedSeconds  = now()->diffInSeconds($examSession->started_at);
        $allocatedSeconds = $exam->duration_minutes * 60;
        $remainingSeconds = (int) floor(max(0, $allocatedSeconds - $elapsedSeconds));

        if ($remainingSeconds <= 0) {
            $this->autoFinish($examSession);
            return redirect()->route('student.exam.finished', $examId);
        }

        return view('student.show', compact('exam', 'examSession', 'remainingSeconds'));
    }

    /**
     * Endpoint selesaikan ujian secara manual (submit siswa).
     */
    public function finish(Request $request, int $examId)
    {
        $sessionId   = Session::get('exam_session_id');
        $examSession = StudentExamSession::where('id', $sessionId)
                                         ->where('exam_id', $examId)
                                         ->firstOrFail();

        if (!$examSession->is_finished) {
            $examSession->update([
                'is_finished'   => true,
                'finished_at'   => now(),
                'finish_reason' => $request->get('reason', 'manual'),
            ]);
        }

        // Hapus session data ujian
        Session::forget(['exam_session_id', 'exam_session_exam_id']);

        return response()->json([
            'success'  => true,
            'message'  => 'Ujian telah diselesaikan.',
            'redirect' => route('student.exam.finished', $examId),
        ]);
    }

    /**
     * Halaman konfirmasi ujian selesai.
     */
    public function finished(int $examId)
    {
        $exam = Exam::findOrFail($examId);
        return view('student.finished', compact('exam'));
    }

    /**
     * Auto-finish dari server (dipanggil internal).
     */
    private function autoFinish(StudentExamSession $examSession): void
    {
        if (!$examSession->is_finished) {
            $examSession->update([
                'is_finished'   => true,
                'finished_at'   => now(),
                'finish_reason' => 'auto',
            ]);
        }
    }
}
