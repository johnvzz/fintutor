<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * User login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * User login form submit
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended(
                ($user->role == 'tutor')
                    ? route('tutor.dashboard')
                    : route('student.dashboard')
            );
        }

        return back()->withErrors([
            'invalidlogin' => 'Invalid login credentials'
        ]);
    }

    /**
     * User registration form
     */
    public function showRegistrationForm()
    {
        return view('signup');
    }

    /**
     * User registration
     */
    public function register(RegisterUserRequest $request)
    {
        $input_data = $request->validated();

        // dd($input_data);

        $user = User::create($input_data);

        Auth::login($user);

        $request->session()->regenerate();

        if ($user->role == 'tutor') {
            return redirect()->route('tutor.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    /**
     * User session logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
