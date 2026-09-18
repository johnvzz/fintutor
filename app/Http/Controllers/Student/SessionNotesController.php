<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TutorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SessionNotesController extends Controller
{
    /**
     * Display a listing of all session notes.
     */
    public function index()
    {
        $session_notes = TutorSession::select(
            'id',
            'topic',
            'scheduled_at',
            'start_time',
            'end_time',
            'homework',
            'session_summary',
            'next_focus'
        )
            ->where('student_id', auth()->user()->id)
            ->where('status', 'completed')
            ->latest('scheduled_at')
            ->get();

        return view('student.session_notes_list', compact('session_notes'));
    }

    public function show(string $id)
    {
        $session = TutorSession::findOrFail($id);

        Gate::authorize('isStudentSession', $session);

        return view('student.session_note_detail', compact('session'));
    }
}
