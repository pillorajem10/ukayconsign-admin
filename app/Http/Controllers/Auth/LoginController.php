<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard'); // Redirect to the dashboard if authenticated
        }

        return view('pages.login'); // Show the login form if not authenticated
    }

    // Handle login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Get the authenticated user

            // Check if the user role is 'admin'
            if ($user->role !== 'admin') {
                Auth::logout(); // Log the user out
                return back()->withErrors([
                    'email' => 'You are not authorized to access this webpage.',
                ]);
            }

            return redirect()->intended('/dashboard'); // Redirect to the dashboard if authorized
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
