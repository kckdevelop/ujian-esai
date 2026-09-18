<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamImage extends Model
{
    protected $fillable = [
        'exam_id',
        'image_path',
        'sort_order',
        'duration_seconds',
    ];

    protected $casts = [
        'sort_order'       => 'integer',
        'duration_seconds' => 'integer',
    ];

    /**
     * Ujian yang memiliki gambar ini.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Mendapatkan URL lengkap gambar.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
