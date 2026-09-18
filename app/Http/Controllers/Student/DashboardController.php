<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard view
     */
    public function index()
    {
        $upcoming_sessions = auth()->user()
            ->studentSessions()
            ->where('status', 'scheduled')
            ->count();

        $completed_sessions = auth()->user()
            ->studentSessions()
            ->where('status', 'completed')
            ->count();

        $homework_count = auth()->user()
            ->studentSessions()
            ->where('status', 'completed')
            ->count();

        return view('student.dashboard', compact(['upcoming_sessions', 'completed_sessions', 'homework_count']));
    }
}
