<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom duration_seconds untuk timer per-soal.
     * Null = tidak ada batas waktu khusus per soal.
     */
    public function up(): void
    {
        Schema::table('exam_images', function (Blueprint $table) {
            $table->unsignedInteger('duration_seconds')->nullable()->after('sort_order')
                  ->comment('Durasi tampil soal dalam detik. NULL = tanpa batas waktu per soal.');
        });
    }

    public function down(): void
    {
        Schema::table('exam_images', function (Blueprint $table) {
            $table->dropColumn('duration_seconds');
        });
    }
};
