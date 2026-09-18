<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\HomeworkController;
use App\Http\Controllers\Student\SessionNotesController;
use App\Http\Controllers\Tutor\DashboardController as TutorDashboard;
use App\Http\Controllers\Tutor\StudentController;
use App\Http\Controllers\Tutor\TutorSessionController;
use App\Http\Controllers\Student\StudentSessionController;
use App\Http\Controllers\Tutor\ProfileController as TutorProfile;
use App\Http\Controllers\Student\ProfileController as StudentProfile;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

// Guest routes
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
});


// Routes restricted to "tutor" role
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')
    ->group(function () {

        Route::get('/dashboard', [TutorDashboard::class, 'index'])->name('dashboard');

        Route::resource('sessions', TutorSessionController::class);

        Route::post(
            'sessions/generate-presessionplan',
            [TutorSessionController::class, 'generatePreSessionPlan']
        )->name('sessions.generate-pre-session-plan');

        Route::post('sessions/start-session', [TutorSessionController::class, 'startSession'])
            ->name('sessions.start-session');

        Route::get('sessions/live-session/{session}', [TutorSessionController::class, 'liveSession'])
            ->name('sessions.livesession');

        Route::post('sessions/savenotes/{session}', [TutorSessionController::class, 'saveLiveNotes'])
            ->name('sessions.savenotes');

        Route::post('sessions/end-session/{session}', [TutorSessionController::class, 'endSession'])
            ->name('sessions.endsession');

        Route::post('sessions/generatedebrief/{session}', [TutorSessionController::class, 'regenerateDebrief'])
            ->name('sessions.generatedebrief');

        Route::get('sessions/generate-summary/{session}', [TutorSessionController::class, 'generateProgressSummary'])
            ->name('sessions.generatesummary');

        // Routes related to Students section for 'tutor' role
        Route::resource('students', StudentController::class);

        Route::get('/profile', [TutorProfile::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [TutorProfile::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [TutorProfile::class, 'updatePassword'])
            ->name('profile.update.password');
    });


// Routes restricted to "student" role
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')
    ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        Route::get('/upcoming-sessions', [StudentSessionController::class, 'index'])
            ->name('upcoming-sessions');
        Route::get('/upcoming-session/{session}', [StudentSessionController::class, 'show'])
            ->name('upcoming-sessions.show');

        Route::get('/session-notes', [SessionNotesController::class, 'index'])->name('session-notes');
        Route::get('/session-notes/{session}', [SessionNotesController::class, 'show'])
            ->name('session-notes.show');

        Route::get('/homeworks', [HomeworkController::class, 'index'])->name('homeworks');
        Route::get('/homeworks/{session}', [HomeworkController::class, 'show'])->name('homeworks.show');

        Route::get('/profile', [StudentProfile::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [StudentProfile::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [StudentProfile::class, 'updatePassword'])->name('profile.update.password');
    });


//Routes for any logged-in user
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
