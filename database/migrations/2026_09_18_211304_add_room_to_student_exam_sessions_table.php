<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom room untuk mencatat ruang ujian yang dipilih siswa.
     */
    public function up(): void
    {
        Schema::table('student_exam_sessions', function (Blueprint $table) {
            $table->string('room', 50)->nullable()->after('exam_id');
        });
    }

    public function down(): void
    {
        Schema::table('student_exam_sessions', function (Blueprint $table) {
            $table->dropColumn('room');
        });
    }
};
