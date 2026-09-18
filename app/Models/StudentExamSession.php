<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExamSession extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'room',
        'student_name',
        'student_class',
        'started_at',
        'finished_at',
        'is_finished',
        'finish_reason',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
        'is_finished'  => 'boolean',
    ];

    /**
     * Siswa (User) pemilik sesi ujian ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Paket ujian yang sedang dikerjakan.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Menghitung sisa detik berdasarkan started_at & durasi ujian.
     */
    public function getRemainingSecondsAttribute(): int
    {
        if ($this->is_finished) return 0;

        $elapsed   = now()->diffInSeconds($this->started_at, false);
        $allocated = $this->exam->duration_minutes * 60;
        $remaining = $allocated + $elapsed; // elapsed is negative when now > started_at

        return max(0, $remaining);
    }
}
