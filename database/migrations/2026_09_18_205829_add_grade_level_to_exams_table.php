<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom grade_level untuk memisahkan paket ujian per tingkat kelas.
     */
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('grade_level', 50)->nullable()->after('title')
                  ->comment('Tingkat kelas ujian, misal: Kelas 10, Kelas 11, Kelas 12');
            $table->index('grade_level');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex(['grade_level']);
            $table->dropColumn('grade_level');
        });
    }
};
