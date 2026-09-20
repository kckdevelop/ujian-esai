<?php

use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autentikasi Admin
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman Siswa / Umum
|--------------------------------------------------------------------------
*/

Route::get('/', [StudentExamController::class, 'index'])->name('student.index');
Route::post('/exam/token', [StudentExamController::class, 'validateToken'])->name('student.exam.token');
Route::get('/exam/{examId}/start', [StudentExamController::class, 'show'])->name('student.exam.show');
Route::post('/exam/{examId}/finish', [StudentExamController::class, 'finish'])->name('student.exam.finish');
Route::get('/exam/{examId}/finished', [StudentExamController::class, 'finished'])->name('student.exam.finished');

/*
|--------------------------------------------------------------------------
| Panel Admin (Dilindungi Auth Middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard Admin (redirect ke daftar ujian)
    Route::get('/', fn () => redirect()->route('admin.exams.index'))->name('dashboard');

    // CRUD Paket Ujian
    Route::get('/exams', [AdminExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [AdminExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [AdminExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [AdminExamController::class, 'edit'])->name('exams.edit');
    Route::get('/exams/{exam}/preview', [AdminExamController::class, 'preview'])->name('exams.preview');
    Route::put('/exams/{exam}', [AdminExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [AdminExamController::class, 'destroy'])->name('exams.destroy');

    // Manajemen Gambar Soal
    Route::post('/exams/{exam}/images', [AdminExamController::class, 'uploadImages'])->name('exams.images.upload');
    Route::post('/exams/{exam}/images/reorder', [AdminExamController::class, 'reorderImages'])->name('exams.images.reorder');
    Route::post('/exams/{exam}/images/batch-duration', [AdminExamController::class, 'batchUpdateDuration'])->name('exams.images.batch-duration');
    Route::patch('/images/{image}/duration', [AdminExamController::class, 'updateImageDuration'])->name('images.duration');
    Route::delete('/images/{image}', [AdminExamController::class, 'deleteImage'])->name('images.delete');

    // Monitoring Peserta / Sesi Ujian
    Route::get('/exams/{exam}/sessions', [AdminExamController::class, 'sessions'])->name('exams.sessions');
    Route::delete('/sessions/{session}', [AdminExamController::class, 'destroySession'])->name('sessions.destroy');

    // Regenerate Token
    Route::post('/exams/{exam}/regenerate-token', [AdminExamController::class, 'regenerateToken'])->name('exams.regenerate-token');

    // Ubah Password Admin
    Route::get('/password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [AdminPasswordController::class, 'update'])->name('password.update');
});
