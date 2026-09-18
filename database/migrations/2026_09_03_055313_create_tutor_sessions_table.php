<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tutor_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('topic', 255);
            $table->date('scheduled_at');
            $table->string('start_time', 20);
            $table->string('end_time', 20);
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('student_id')->constrained('users');
            $table->enum('status', ['scheduled', 'inprogress', 'completed', 'ai_reviewed'])
                ->default('scheduled');
            $table->text('additional_instructions')->nullable();
            $table->text('objectives')->nullable();
            $table->text('lesson_outline')->nullable();
            $table->text('practice_questions')->nullable();
            $table->text('live_notes')->nullable();
            $table->text('session_summary')->nullable();
            $table->text('homework')->nullable();
            $table->text('next_focus')->nullable();
            $table->text('progress_summary')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_sessions');
    }
};
