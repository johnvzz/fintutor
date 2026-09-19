<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StudentFormRequest;

class ProfileController extends Controller
{
    /**
     * Display student profile in edit mode
     */
    public function edit()
    {
        $user = auth()->user()->load('student');

        return view('student.profile', compact('user'));
    }

    /**
     * Update basic details
     */
    public function update(StudentFormRequest $request)
    {
        $user = auth()->user();


        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        Student::upsert([
            [
                'user_id' => $user->id,
                'dob' => $request->filled('dob')
                    ? Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')
                    : null,
                'grade' => $request->grade,
                'school' => $request->school,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
            ],
        ], ['user_id'], [
            'dob',
            'grade',
            'school',
            'parent_name',
            'parent_phone',
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = auth()->user();

        $user->update([
            'password' => $request->password
        ]);

        return back()->with('success', 'Password updated successfully');
    }
}
