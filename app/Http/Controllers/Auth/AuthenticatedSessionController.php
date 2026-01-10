<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     *
     * 📚 GET /login — returns the view at resources/views/auth/login.blade.php
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * 📚 POST /login
     * Auth::attempt() looks up the user by email, then uses Hash::check()
     * to compare the submitted password against the bcrypt hash in the DB.
     * On success it writes the user's ID to the session.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Check if the account has been approved by an admin.
        if (! Auth::user()->isApproved()) {
            Auth::logout();
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Your account is pending admin approval. You will be able to log in once an admin approves your registration.']);
        }

        // Regenerate the session ID to prevent session-fixation attacks.
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Destroy the authenticated session (log out).
     *
     * 📚 POST /logout
     * Auth::logout() removes the user from the session.
     * invalidate() destroys the session data entirely.
     * regenerateToken() issues a fresh CSRF token.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
