<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TutorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeworkController extends Controller
{
    /**
     * Display listing of assigned homeworks
     */
    public function index()
    {
        $homeworks = TutorSession::select('id', 'topic', 'scheduled_at', 'start_time', 'end_time', 'homework')
            ->where('student_id', auth()->user()->id)
            ->where('status', 'completed')
            ->latest('scheduled_at')
            ->get();

        return view('student.homework_list', compact('homeworks'));
    }

    /**
     * Display homework detail page 
     */
    public function show(string  $id)
    {
        $session = TutorSession::findOrFail($id);

        Gate::authorize('isStudentSession', $session);

        return view('student.homework_detail', compact('session'));
    }
}
