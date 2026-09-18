<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('student_name')->nullable();
            $table->string('student_class')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->string('finish_reason')->nullable(); // 'manual' or 'auto'
            $table->timestamps();

            $table->index(['exam_id', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exam_sessions');
    }
};
