<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'grade_level',
        'token',
        'start_time',
        'end_time',
        'duration_minutes',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_time'   => 'datetime',
        'end_time'     => 'datetime',
        'is_active'    => 'boolean',
    ];

    /**
     * Gambar-gambar soal ujian (diurutkan berdasarkan sort_order).
     */
    public function images(): HasMany
    {
        return $this->hasMany(ExamImage::class)->orderBy('sort_order');
    }

    /**
     * Semua sesi ujian siswa untuk paket ini.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(StudentExamSession::class);
    }

    /**
     * Scope: ujian yang sedang aktif berdasarkan jadwal hari ini.
     */
    public function scopeActiveToday($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where('start_time', '<=', $now)
                     ->where('end_time', '>=', $now);
    }

    /**
     * Scope: ujian yang aktif (status aktif & berlangsung hari ini).
     */
    public function scopeScheduledToday($query)
    {
        $today = now()->toDateString();
        return $query->where('is_active', true)
                     ->whereDate('start_time', '<=', $today)
                     ->whereDate('end_time', '>=', $today);
    }
}
