<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard view
     */
    public function index()
    {

        $upcomingSessions = auth()->user()
            ->tutorSessions()
            ->where('status', 'scheduled')
            ->count();

        $completedSessions = auth()->user()
            ->tutorSessions()
            ->where('status', 'completed')
            ->count();

        $todaySessions = auth()->user()
            ->tutorSessions()
            ->where('status', 'scheduled')
            ->where('scheduled_at', date('Y-m-d'))
            ->count();

        return view('tutor.dashboard', compact(['upcomingSessions', 'completedSessions', 'todaySessions']));
    }
}
