<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TutorSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class StudentSessionController extends Controller
{
    /**
     *  Retrieves upcoming sessions for students
     */
    public function index()
    {
        $sessions = auth()->user()
            ->studentSessions()
            ->where('status', 'scheduled')
            ->with('tutor')
            ->latest()
            ->paginate(10);

        return view('student.upcoming_session_list', compact('sessions'));
    }

    public function show(string $id)
    {
        $session = TutorSession::findOrFail($id);

        Gate::authorize('isStudentSession', $session);

        $session->duration = Carbon::parse($session->start_time)
            ->diffInMinutes(Carbon::parse($session->end_time));

        return view('student.upcoming_session_detail', compact('session'));
    }
}
