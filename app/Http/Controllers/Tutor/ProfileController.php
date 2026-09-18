<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display tutor profile in edit mode
     */
    public function edit()
    {
        $user = auth()->user();

        return view('tutor.profile', compact('user'));
    }

    /**
     * Update basic details
     */
    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:20|regex:/^\+?[0-9-]+$/',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
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
