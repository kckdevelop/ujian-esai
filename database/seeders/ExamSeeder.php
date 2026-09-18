<?php

namespace Database\Seeders;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Ujian aktif sekarang (untuk demo)
        Exam::create([
            'title'            => 'Ujian Matematika Kelas XII',
            'token'            => 'MTK2024',
            'description'      => 'Ujian Akhir Semester Genap - Mata Pelajaran Matematika',
            'start_time'       => $now->copy()->subMinutes(5),
            'end_time'         => $now->copy()->addHours(2),
            'duration_minutes' => 90,
            'is_active'        => true,
        ]);

        // Ujian lain aktif sekarang
        Exam::create([
            'title'            => 'Ujian Bahasa Indonesia',
            'token'            => 'BIN2024',
            'description'      => 'Ujian Kompetensi Bahasa Indonesia',
            'start_time'       => $now->copy()->subMinutes(10),
            'end_time'         => $now->copy()->addHours(1)->addMinutes(30),
            'duration_minutes' => 60,
            'is_active'        => true,
        ]);

        // Ujian nonaktif
        Exam::create([
            'title'            => 'Ujian IPA Kelas XI (Nonaktif)',
            'token'            => 'IPA2024',
            'description'      => 'Ujian ini belum diaktifkan',
            'start_time'       => $now->copy()->addDay(),
            'end_time'         => $now->copy()->addDay()->addHours(2),
            'duration_minutes' => 120,
            'is_active'        => false,
        ]);
    }
}
