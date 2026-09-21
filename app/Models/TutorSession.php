<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorSession extends Model
{

    /**
     * The attributes that are mass assignable
     * 
     * @var list<string>
     */
    protected $fillable = [
        'topic',
        'scheduled_at',
        'start_time',
        'end_time',
        'tutor_id',
        'student_id',
        'additional_instructions',
        'status',
        'objectives',
        'lesson_outline',
        'practice_questions',
        'live_notes',
        'session_summary',
        'homework',
        'next_focus',
        'progress_summary',
        'started_at',
        'ended_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'date',
            'ended_at' => 'datetime',
            'objectives' => 'array',
            'lesson_outline' => 'array',
            'practice_questions' => 'array',
            'homework' => 'array',
        ];
    }

    /** Get the student associated with this session. */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** Get the tutor associated with this session. */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
